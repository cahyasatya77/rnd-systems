<?php

namespace backend\controllers;

use common\models\Meetingregistrasi;
use backend\models\MeetingregistrasiSearch;
use backend\models\Model;
use backend\models\Modelstatis;
use common\models\Masterdokumen;
use common\models\Masterjenismeetingreg;
use common\models\Mastervariasi;
use common\models\Mastervariasidokumen;
use common\models\Meetingdokumen;
use common\models\Meetingkategori;
use common\models\Options;
use common\models\User;
use Exception;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * MeetingregistrasiController implements the CRUD actions for Meetingregistrasi model.
 */
class MeetingregistrasiController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'view', 'create', 'update', 'ednie', 'viewapprove', 'delete'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [
                                'kirim', 'create', 'createeval', 'updatevariasi', 'updateeval', 'indexrevisi', 
                                'updaterevisi', 'viewrevisi', 'kirimrevisi', 'updatepic', 'updatepiceval'
                            ],
                            'allow' => Yii::$app->user->idDept == 'D0001',
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approve', 'revisi', 'indexmanager'],
                            'allow' => Yii::$app->user->idDept == 'D0001' and Yii::$app->user->level == 'manager',
                            'roles' => ['@']
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Get data ed NIE from db_pm
     * 
     * @param $id ID
     */
    public function actionEdnie($id)
    {
        $connection = Yii::$app->db_pm;
        $sql = "SELECT p.nama_produk, p.id, n.nomor_nie, MAX(n.ed) as ed_nie
                FROM tbl_produk p
                JOIN tbl_nie n ON p.kd_produk = n.kd_produk 
                WHERE p.id = :id";
        $data = $connection->createCommand($sql)->bindParam(':id', $id)->queryOne();

        return Json::encode($data);
    }

    /**
     * Lists all Meetingregistrasi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $status = Options::find()->where(['table' => 'meeting_reg'])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
            'status' => $status,
        ]);
    }

    public function actionIndexmanager()
    {
        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->searchManager($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $status = Options::find()->where(['table' => 'meeting_reg'])->all();

        return $this->render('manager/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
            'status' => $status,
        ]);
    }

    public function actionIndexrevisi()
    {
        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->searchRevisi($this->request->queryParams);

        return $this->render('revisi/indexrevisi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Meetingregistrasi model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new MeetingregistrasiSearch();
        // data Karegori Variasi
        $dataVariasi = $searchModel->searchDokumenVariasi($this->request->queryParams, $model->id);
        // data Evaluasi Reg
        $dataEvaluasi = $searchModel->searchDokumenEvaluasi($this->request->queryParams, $model->id);

        return $this->render('view', [
            'model' => $model,
            'dataVariasi' => $dataVariasi,
            'dataEvaluasi' => $dataEvaluasi,
        ]);
    }

    public function actionViewrevisi($id) 
    {
        $model = $this->findModel($id);

        $searchModel = new MeetingregistrasiSearch();
        // data Karegori Variasi
        $dataVariasi = $searchModel->searchDokumenVariasi($this->request->queryParams, $model->id);
        // data Evaluasi Reg
        $dataEvaluasi = $searchModel->searchDokumenEvaluasi($this->request->queryParams, $model->id);

        return $this->render('revisi/view', [
            'model' => $model,
            'dataVariasi' => $dataVariasi,
            'dataEvaluasi' => $dataEvaluasi,
        ]);
    }

    /**
     * Displays a single Meetingregistrasi model
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewapprove($id)
    {
        $model = $this->findDokumen($id);

        return $this->render('view_approve', [
            'model' => $model,
        ]);
    }

    /**
     * Display a multiple model relation from Meetingregistrasi Model.
     * And update a existing Meetingregistrasi model and the relation table.
     * If update successfull, the browser will be redirected to the 'indexmanager' page.
     * @param $id ID
     * @return mixed
     * @throws NotFoundHttpException If the model canot be found
     */
    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        $searchModel = new MeetingregistrasiSearch();
        // data Kategori Variasi
        $dataVariasi = $searchModel->searchDokumenVariasi($this->request->queryParams, $model->id);
        // data Evaluasi Reg
        $dataEvaluasi = $searchModel->searchDokumenEvaluasi($this->request->queryParams, $model->id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $status = Options::find()->where(['table' => 'meeting_reg'])->andWhere(['deskripsi' => 'open'])->one();
                $model->id_status = $status->id;
                $model->approve_manager = new Expression('NOW()');
                $success = $model->save();

                if ($success) {
                    $model_dokumen = Meetingdokumen::find()
                        ->joinWith('kategori.meeting')
                        ->where(['meeting_registrasi.id' => $model->id])
                        ->all();
                    foreach ($model_dokumen as $dokumen) {
                        $dokumen->status = 'open';
                        $dokumen->status_pic = 'open';
                        if (!($success = $dokumen->save(false))) {
                            break;
                        }
                    }
                }

                if ($success) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Notulen meeting telah diapprove dan dikirim ke PIC terkait.');
                    return $this->redirect(['indexmanager']);
                }

            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', 'Error your transaction function'. $e->getMessage());
                $transaction->rollBack();
            }
        }

        return $this->render('manager/approve', [
            'model' => $model,
            'dataVariasi' => $dataVariasi,
            'dataEvaluasi' => $dataEvaluasi,
        ]);
    }

    /**
     * Update a existing Meetingregistasi model.
     * For revisi komitmen from RnD Manager to Registrasi staff.
     * If update successfull, the browser will be redirected to the 'indexmanager' page.
     * @param $id Id
     * @return mixed
     * @throws NotFoundHttpException if the model canot be found
     */
    public function actionRevisi($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Meetingregistrasi::SCENARIO_REVISI;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $status = Options::find()->where(['table' => 'meeting_reg'])->andWhere(['deskripsi' => 'revisi'])->one();
            $model->id_status = $status->id;
            $model->rnd_manager = null;
            $model->approve_manager = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Meeting registasi status "Revisi" dan dikembalikan ke registrasi staff');
                return $this->redirect(['indexmanager']);
            }
        }

        return $this->render('manager/revisi', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Meetingregistrasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new Meetingregistrasi();
        $model_kategori = [new Meetingkategori];

        // Data choice for the Activeform
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $variasi = Mastervariasi::find()
                ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
                ->where(['status' => 'active'])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model_kategori = Model::createMultiple(Meetingkategori::className());
            Model::loadMultiple($model_kategori, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = Model::validateMultiple($model_kategori) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $sql_nama_produk = "SELECT nama_produk FROM tbl_produk WHERE id=:id";
                    $id_produk = $model->id_produk;
                    $nama_produk = $connection->createCommand($sql_nama_produk)
                                    ->bindParam(':id', $id_produk)
                                    ->queryOne();

                    $model->id_transaksi = $model->generateKode();
                    $model->nama_produk = $nama_produk['nama_produk'];
                    $model->tanggal_pembuatan = new Expression('NOW()');
                    $status = Options::find()->where(['table' => 'meeting_reg'])->andWhere(['deskripsi' => 'draft'])->one();
                    $model->id_status = $status->id;
                    $success = $model->save();

                    if ($success) {
                        foreach ($model_kategori as $kategori) {
                            if ($success == false) {
                                break;
                            }

                            $kategori->id_meeting = $model->id;
                            $kode_variasi = Mastervariasi::findOne($kategori->id_variasi);
                            $kategori->kode = $kode_variasi->kode;
                            $kategori->deskripsi = $kode_variasi->deskripsi;
                            $jenis_kategori = Masterjenismeetingreg::find()->where(['deskripsi' => 'Kategori Variasi'])->one();
                            $kategori->id_jenis_meeting = $jenis_kategori->id;
                            $kategori->status = 'open';

                            if ($success = $kategori->save()) {
                                $detail_dokumen = Mastervariasidokumen::find()->where(['id_master_variasi' => $kategori->id_variasi])->all();
                                foreach ($detail_dokumen as $detail) {
                                    // master dokumen
                                    $master_dok = Masterdokumen::findOne($detail->id_master_dokumen);
                                    // save to meeting dokumen
                                    $model_dokumen = new Meetingdokumen();
                                    $model_dokumen->id_kategori = $kategori->id;
                                    $model_dokumen->id_dokumen = $master_dok->id;
                                    $model_dokumen->kode_dokumen = $master_dok->kode;
                                    $model_dokumen->dokumen = $master_dok->deskripsi;
                                    $model_dokumen->status = 'draft';
                                    if (!($success = $model_dokumen->save(false))) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }

                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Error your transaction function. Please check your header model');
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Notulen meeting telah dibuat, Lakukan update PIC');
                        return $this->redirect(['updatepic', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('error', 'Error your transaction function'. $e->getMessage());
                    $transaction->rollBack();
                }
            } else {
                Yii::$app->session->setFlash('error', 'Save list not valid. check your validation model.');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'model_kategori' => $model_kategori,
            'produk' => $produk,
            'pic' => $pic,
            'variasi' => $variasi,
        ]);
    }

    public function actionUpdatepic($id) 
    {
        $model = $this->findModel($id);
        $model_kategori = $model->meetingkategorivar;
        $model_dokumen = [];
        $oldDokumen = [];

        if (!empty($model_kategori)) {
            foreach ($model_kategori as $i => $kategori) {
                $dokumen = $kategori->meetingdokumen;
                $model_dokumen[$i] = $dokumen;
                $oldDokumen = ArrayHelper::merge(ArrayHelper::index($dokumen, 'id'), $oldDokumen);
            }
        }

        // Data choice for the ActiveForm
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $variasi_dok = Masterdokumen::find()
                ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
                ->where(['status' => 'active'])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            // reset
            $model_dokumen = [];

            $oldKateId = ArrayHelper::map($model_kategori, 'id', 'id');
            $model_kategori = Model::createMultiple(Meetingkategori::className(), $model_kategori);
            Model::loadMultiple($model_kategori, Yii::$app->request->post());
            $deleteKategori = array_diff($oldKateId, array_filter(ArrayHelper::map($model_kategori, 'id', 'id')));

            // validate
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_kategori) && $valid;

            $dokId = [];
            if (isset($_POST['Meetingdokumen'][0][0])) {
                foreach ($_POST['Meetingdokumen'] as $i => $dok) {
                    $dokId = ArrayHelper::merge($dokId, array_filter(ArrayHelper::getColumn($dok, 'id')));
                    foreach ($dok as $d => $dokumen) {
                        $data['Meetingdokumen'] = $dokumen;
                        $model_dok = (isset($dokumen['id']) && isset($oldDokumen[$dokumen['id']])) ? $oldDokumen[$dokumen['id']] : new Meetingdokumen;
                        $model_dok->load($data);
                        $model_dokumen[$i][$d] = $model_dok;
                        $valid = $model_dok->validate();
                    }
                }
            }

            $oldDokid = ArrayHelper::getColumn($oldDokumen, 'id');
            $deleteDok = array_diff($oldDokid, $dokId);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteDok)) {
                            Meetingdokumen::deleteAll(['id' => $deleteDok]);
                        }

                        foreach ($model_kategori as $i => $kategori) {
                            if ($success == false) {
                                break;
                            }

                            $kategori->id_meeting = $model->id;
                            if (!($success = $kategori->save(false))) {
                                break;
                            }

                            if (isset($model_dokumen[$i]) && is_array($model_dokumen[$i])) {
                                foreach ($model_dokumen[$i] as $d => $dokumen) {
                                    $dokumen->id_kategori = $kategori->id;
                                    $master_dok = Masterdokumen::findOne($dokumen->id_dokumen);
                                    $dokumen->kode_dokumen = $master_dok->kode;
                                    $dokumen->dokumen = $master_dok->deskripsi;
                                    if ($dokumen->isNewRecord) {
                                        $dokumen->status = 'draft';
                                    }

                                    if (!($success = $dokumen->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data PIC dan deadline berhasil ditambahkan.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Function transaction error.'.$e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Model validation is not valid. Please check your model.');
            }
        }

        return $this->render('update_pic', [
            'model' => $model,
            'model_kategori' => $model_kategori,
            'model_dokumen' => $model_dokumen,
            'pic' => $pic,
            'variasi_dok' => $variasi_dok,
        ]);
    }
    
    /**
     * Create a new Meetingkategori model and Meetingdokumen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreateeval($id)
    {
        $model = $this->findModel($id);
        $model_kategori = [new Meetingkategori];

        // data choice to ActiveForm
        $variasi = Mastervariasi::find()
            ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
            ->where(['status' => 'active'])
            ->all();
        
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model_kategori = Model::createMultiple(Meetingkategori::className());
            Model::loadMultiple($model_kategori, Yii::$app->request->post());

            $valid = Model::validateMultiple($model_kategori);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($model_kategori as $i => $kategori) {
                        $kategori->id_meeting = $model->id;
                        $jenis_kategori = Masterjenismeetingreg::find()->where(['deskripsi' => 'Evaluasi Reg'])->one();
                        $kategori->id_jenis_meeting = $jenis_kategori->id;
                        $kategori->status = 'open';
                        $master_variasi = Mastervariasi::findOne($kategori->id_variasi);
                        $kategori->kode = $master_variasi->kode;
                        $kategori->deskripsi = $master_variasi->deskripsi;
                        $success = $kategori->save();
                        
                        if ($success) {
                            $master_dok = Mastervariasidokumen::find()->where(['id_master_variasi' => $kategori->id_variasi])->all();
                            foreach ($master_dok as $dok) {
                                $dokumen = new Meetingdokumen();
                                $master_dokumen = Masterdokumen::findOne($dok->id_master_dokumen);
                                $dokumen->id_kategori = $kategori->id;
                                $dokumen->id_dokumen = $master_dokumen->id;
                                $dokumen->kode_dokumen = $master_dokumen->kode;
                                $dokumen->dokumen = $master_dokumen->deskripsi;
                                $dokumen->status = 'draft';
                                if (!($success = $dokumen->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data Meeting Evaluasi Reg berhasil disimpan, Lakukan update PIC.');
                        return $this->redirect(['updatepiceval', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                     Yii::$app->session->setFlash('error', 'Error your function transaaction, please check your function.');
                    $transaction->rollBack();
                }
            } else {
                Yii::$app->session->setFlash('error', 'Save list not valid. check your validation model.');
            }
        } 

        return $this->render('create_eval_new/create', [
            'model' => $model,
            'model_kategori' => $model_kategori,
            'variasi' => $variasi
        ]);
    }

    /**
     * Updates redirected to model Meeting dokumen.
     * If update is successful, the browser will ber redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdatepiceval($id)
    {
        $model = $this->findModel($id);
        $model_kategori = $model->meetingkategorieval;
        $model_dokumen = [];
        $oldDokumen = [];

        if (!empty($model_kategori)) {
            foreach ($model_kategori as $i => $kategori) {
                $dokumen = $kategori->meetingdokumen;
                $model_dokumen[$i] = $dokumen;
                $oldDokumen = ArrayHelper::merge(Arrayhelper::index($dokumen, 'id'), $oldDokumen);
            }
        }

        // Data choice for the ActiveForm
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $variasi_dok = Masterdokumen::find()
                ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
                ->where(['status' => 'active'])->all();
            
        if ($this->request->isPost && $model->load($this->request->post())) {
            // reset
            $model_dokumen = [];

            $oldKateId = ArrayHelper::map($model_kategori, 'id', 'id');
            $model_kategori = Model::createMultiple(Meetingkategori::className(), $model_kategori);
            Model::loadMultiple($model_kategori, Yii::$app->request->post());
            $deleteKategori = array_diff($oldKateId, array_filter(ArrayHelper::map($model_kategori, 'id', 'id')));

            // validate 
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_kategori) && $valid;

            $dokId = [];
            if (isset($_POST['Meetingdokumen'][0][0])) {
                foreach ($_POST['Meetingdokumen'] as $i => $dok) {
                    $dokId = ArrayHelper::merge($dokId, array_filter(ArrayHelper::getColumn($dok, 'id')));
                    foreach ($dok as $d => $dokumen) {
                        $data['Meetingdokumen'] = $dokumen;
                        $model_dok = (isset($dokumen['id']) && isset($oldDokumen[$dokumen['id']])) ? $oldDokumen[$dokumen['id']] : new Meetingdokumen;
                        $model_dok->load($data);
                        $model_dokumen[$i][$d] = $model_dok;
                        $valid = $model_dok->validate();
                    }
                }
            }

            $oldDokid = ArrayHelper::getColumn($oldDokumen, 'id');
            $deleteDok = array_diff($oldDokid, $dokId);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteDok)) {
                            Meetingdokumen::deleteAll(['id' => $deleteDok]);
                        }

                        foreach ($model_kategori as $i => $kategori) {
                            if ($success == false) {
                                break;
                            }

                            $kategori->id_meeting = $model->id;
                            if (!($success = $kategori->save(false))) {
                                break;
                            }

                            if (isset($model_dokumen[$i]) && is_array($model_dokumen[$i])) {
                                foreach ($model_dokumen[$i] as $d => $dokumen) {
                                    $dokumen->id_kategori = $kategori->id;
                                    $master_dok = Masterdokumen::findOne($dokumen->id_dokumen);
                                    $dokumen->kode_dokumen = $master_dok->kode;
                                    $dokumen->dokumen = $master_dok->deskripsi;
                                    if ($dokumen->isNewRecord) {
                                        $dokumen->status = 'draft';
                                    }

                                    if (!($success = $dokumen->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data PIC dan deadline berhasil ditambahkan.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Function transaction error.'.$e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Model validation is not valid. Please check your model.');
            }
        }

        return $this->render('create_eval_new/update_pic', [
            'model' => $model,
            'model_kategori' => $model_kategori,
            'model_dokumen' => $model_dokumen,
            'pic' => $pic,
            'variasi_dok' => $variasi_dok,
        ]);
    }

    /**
     * Updates redirected to model Meeting Registrasi.
     * If update is successful, the browser will be redirected to the 'update' action.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_kategori = $model->meetingkategori;
        $model_dokumen = [];
        $oldDokumen = [];

        if (!empty($model_kategori)) {
            foreach ($model_kategori as $i => $kategori) {
                $dokumen = $kategori->meetingdokumen;
                $model_dokumen[$i] = $dokumen;
                $oldDokumen = ArrayHelper::merge(ArrayHelper::index($dokumen, 'id'), $oldDokumen);
            }
        }

        // Data choice for the ActiveForm
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $jenis_meeting = Masterjenismeetingreg::find()->all();
        $variasi = Mastervariasi::find()
            ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
            ->where(['status' => 'active'])
            ->all();
        $variasi_dok = Masterdokumen::find()
                ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
                ->where(['status' => 'active'])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {

            // reset
            $model_dokumen = [];

            $oldKateId = ArrayHelper::map($model_kategori, 'id', 'id');
            $model_kategori = Model::createMultiple(Meetingkategori::className(), $model_kategori);
            Model::loadMultiple($model_kategori, Yii::$app->request->post());
            $deleteKategori = array_diff($oldKateId, array_filter(ArrayHelper::map($model_kategori, 'id', 'id')));

            // validate
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_kategori) && $valid;

            $dokId = [];
            if (isset($_POST['Meetingdokumen'][0][0])) {
                foreach ($_POST['Meetingdokumen'] as $i => $dok) {
                    $dokId = ArrayHelper::merge($dokId, array_filter(ArrayHelper::getColumn($dok, 'id')));
                    foreach ($dok as $d => $dokumen) {
                        $data['Meetingdokumen'] = $dokumen;
                        $model_dok = (isset($dokumen['id']) && isset($oldDokumen[$dokumen['id']])) ? $oldDokumen[$dokumen['id']] : new Meetingdokumen;
                        $model_dok->load($data);
                        $model_dokumen[$i][$d] = $model_dok;
                        $valid = $model_dok->validate();
                    }
                }
            }

            $oldDokid = ArrayHelper::getColumn($oldDokumen, 'id');
            $deleteDok = array_diff($oldDokid, $dokId);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteKategori)) {
                            Meetingkategori::deleteAll(['id' => $deleteKategori]);
                        }

                        if (!empty($deleteDok)) {
                            Meetingdokumen::deleteAll(['id' => $deleteDok]);
                        }

                        foreach ($model_kategori as $i => $kategori) {
                            if ($success == false) {
                                break;
                            }

                            $kategori->id_meeting = $model->id;
                            $kode_variasi = Mastervariasi::findOne($kategori->id_variasi);
                            $kategori->kode = $kode_variasi->kode;
                            $kategori->deskripsi = $kode_variasi->deskripsi;
                            if ($kategori->isNewRecord) {
                                $kategori->status = 'open';
                            }
                            if (!($success = $kategori->save(false))) {
                                break;
                            }

                            if (isset($model_dokumen[$i]) && is_array($model_dokumen[$i])) {
                                foreach ($model_dokumen[$i] as $d => $dokumen) {
                                    // master dokumen
                                    $master_dok = Masterdokumen::findOne($dokumen->id_dokumen);
                                    // save to meeting dokumen
                                    $dokumen->id_kategori = $kategori->id;
                                    $dokumen->kode_dokumen = $master_dok->kode;
                                    $dokumen->dokumen = $master_dok->deskripsi;
                                    if ($dokumen->isNewRecord) {
                                        $dokumen->status = 'draft';
                                    }

                                    if (!($success = $dokumen->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data Notulen Meeting berhasil dilakukan pembaruan data.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Function transaction error.'.$e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_kategori' => $model_kategori,
            'model_dokumen' => $model_dokumen,
            'produk' => $produk,
            'pic' => $pic,
            'jenis_meeting' => $jenis_meeting,
            'variasi' => $variasi,
            'variasi_dok' => $variasi_dok,
        ]);
    }

    /**
     * Updates redirected to model Meeting Registrasi.
     * Update action for the revisi from Rnd Manager.
     * If update is successful, the browser will be redirected to the 'update' action.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdaterevisi($id)
    {
        $model = $this->findModel($id);
        $model_kategori = $model->meetingkategori;
        $model_dokumen = [];
        $oldDokumen = [];

        if (!empty($model_kategori)) {
            foreach ($model_kategori as $i => $kategori) {
                $dokumen = $kategori->meetingdokumen;
                $model_dokumen[$i] = $dokumen;
                $oldDokumen = ArrayHelper::merge(ArrayHelper::index($dokumen, 'id'), $oldDokumen);
            }
        }

        // Data choice for the ActiveForm
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $jenis_meeting = Masterjenismeetingreg::find()->all();
        $variasi = Mastervariasi::find()
            ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
            ->where(['status' => 'active'])
            ->all();
        $variasi_dok = Masterdokumen::find()
                ->select(['id', 'CONCAT(kode, " - ", deskripsi) kode'])
                ->where(['status' => 'active'])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            // reset
            $model_dokumen = [];

            $oldKateId = ArrayHelper::map($model_kategori, 'id', 'id');
            $model_kategori = Model::createMultiple(Meetingkategori::className(), $model_kategori);
            Model::loadMultiple($model_kategori, Yii::$app->request->post());
            $deleteKategori = array_diff($oldKateId, array_filter(ArrayHelper::map($model_kategori, 'id', 'id')));

            // validate 
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_kategori) && $valid;

            $dokId = [];
            if (isset($_POST['Meetingdokumen'][0][0])) {
                foreach ($_POST['Meetingdokumen'] as $i => $dok) {
                    $dokId = ArrayHelper::merge($dokId, array_filter(ArrayHelper::getColumn($dok, 'id')));
                    foreach ($dok as $d => $dokumen) {
                        $data['Meetingdokumen'] = $dokumen;
                        $model_dok = (isset($dokumen['id']) && isset($oldDokumen[$dokumen['id']])) ? $oldDokumen[$dokumen['id']] : new Meetingdokumen();
                        $model_dok->load($data);
                        $model_dokumen[$i][$d] = $model_dok;
                        $valid = $model_dok->validate();
                    }
                }
            }

            $oldDokid = ArrayHelper::getColumn($oldDokumen, 'id');
            $deleteDok = array_diff($oldDokid, $dokId);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteKategori)) {
                            Meetingkategori::deleteAll(['id' => $deleteKategori]);
                        }

                        if (!empty($deleteDok)) {
                            Meetingdokumen::deleteAll(['id' => $deleteDok]);
                        }

                        foreach ($model_kategori as $i => $kategori) {
                            if ($success == false) {
                                break;
                            }

                            $kategori->id_meeting = $model->id;
                            $kode_variasi = Mastervariasi::findOne($kategori->id_variasi);
                            $kategori->kode = $kode_variasi->kode;
                            $kategori->deskripsi = $kode_variasi->deskripsi;
                            if ($kategori->isNewRecord) {
                                $kategori->status = 'open';
                            }
                            if (!($success = $kategori->save(false))) {
                                break;
                            }

                            if (isset($model_dokumen[$i]) && is_array($model_dokumen[$i])) {
                                foreach ($model_dokumen[$i] as $d => $dokumen) {
                                    // master dokumen
                                    $master_dok = Masterdokumen::findOne($dokumen->id_dokumen);
                                    // save to meeting dokumen
                                    $dokumen->id_kategori = $kategori->id;
                                    $dokumen->kode_dokumen = $master_dok->kode;
                                    $dokumen->dokumen = $master_dok->deskripsi;
                                    if ($dokumen->isNewRecord) {
                                        $dokumen->status = 'draft';
                                    }

                                    if (!($success = $dokumen->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data komitmen berhasil dilakukan pembaruan data.');
                        return $this->redirect(['viewrevisi', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Function transaction error.'.$e->getMessage());
                }
            }
        }

        return $this->render('revisi/update', [
            'model' => $model,
            'model_kategori' => $model_kategori,
            'model_dokumen' => $model_dokumen,
            'produk' => $produk,
            'pic' => $pic,
            'jenis_meeting' => $jenis_meeting,
            'variasi' => $variasi,
            'variasi_dok' => $variasi_dok
        ]);
    }

    /**
     * Deletes an existing Meetingregistrasi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Send to RnD Manager and update an existing Meetingregistrasi model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionKirim($id) 
    {
        $model = $this->findModel($id);
        
        $model->rnd_manager = 3;
        $status = Options::find()->where(['table' => 'meeting_reg'])->andWhere(['deskripsi' => 'menunggu'])->one();
        $model->id_status = $status->id;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dikirm ke R&D Manager, silahkan tunggu proses approve');
            $this->redirect(['index']);
        }
    }

    /**
     * Send revisi from rnd manager
     */
    public function actionKirimrevisi($id) 
    {
        $model = $this->findModel($id);

        $model->rnd_manager = 3;
        $status = Options::find()->where(['table' => 'meeting_reg'])->andWhere(['deskripsi' => 'menunggu'])->one();
        $model->id_status = $status->id;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dikirm ke R&D Manager, silahkan tunggu proses approve');
            $this->redirect(['indexrevisi']);
        }
    }

    /**
     * Finds the Meetingregistrasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Meetingregistrasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meetingregistrasi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findDokumen($id)
    {
        if (($model = Meetingdokumen::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

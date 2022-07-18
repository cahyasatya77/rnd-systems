<?php

namespace backend\controllers;

use backend\models\Model;
use common\models\Registrasikomitmen;
use backend\models\RegistrasikomitmenSearch;
use backend\models\RegkomtindakanSearch;
use common\models\Jnsdok;
use common\models\Masterjeniskomitmen;
use common\models\Options;
use common\models\Regjnskomitmen;
use common\models\Regkomtindakan;
use common\models\User;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * RegistrasikomitmenController implements the CRUD actions for Registrasikomitmen model.
 */
class RegistrasikomitmenController extends Controller
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
                        'kirim' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'bentuksediaan', 'viewapprove'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [
                                'kirim', 'indexmanager', 'revisiindex', 'updaterevisi', 'viewrevisi', 
                                'indexbpom', 'closekomitmen'
                            ],
                            'allow' => Yii::$app->user->idDept == 'D0001',
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['detailapprove', 'revisi'],
                            'allow' => Yii::$app->user->idDept == 'D0001' and Yii::$app->user->level == 'manager',
                            'roles' => ['@']
                        ],
                    ],
                ],

            ]
        );
    }

    /**
     * Get data betuk sedian from db_pm
     * 
     * @param $id ID
     */
    public function actionBentuksediaan($id)
    {
        $connection = Yii::$app->db_pm;
        $sql = "SELECT bentuk_sediaan FROM tbl_produk WHERE id=:id";
        $data = $connection->createCommand($sql)->bindParam(':id', $id)->queryOne();

        return Json::encode($data);
    }

    /**
     * Lists all Registrasikomitmen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistrasikomitmenSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
        ]);
    }

    public function actionIndexmanager()
    {
        $searchModel = new RegistrasikomitmenSearch();
        $dataProvider = $searchModel->searchManager($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();

        return $this->render('manager/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
        ]);
    }

    public function actionRevisiindex()
    {
        $searchModel = new RegistrasikomitmenSearch();
        $dataProvider = $searchModel->searchRevisi($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();

        return $this->render('revisi/indexrevisi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
        ]);
    }

    public function actionIndexbpom()
    {
        $searchModel = new RegistrasikomitmenSearch();
        $dataProvider = $searchModel->searchBpom($this->request->queryParams);

        return $this->render('bpom/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Registrasikomitmen model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        // $model_jenis = Regjnskomitmen::find()->where(['id_komitmen' => $model->id])->all();
        $model_jenis = $model->jenisKomitmen;

        $model_komitmen = new ActiveDataProvider([
            'query' => Regkomtindakan::find()
                        ->join('JOIN', 'reg_jns_komitmen', 'reg_jns_komitmen.id = reg_kom_tindakan.id_jns_komitmen')
                        ->join('JOIN', 'registrasi_komitmen', 'registrasi_komitmen.id = reg_jns_komitmen.id_komitmen')
                        ->where('registrasi_komitmen.id = '.$model->id)
                        ->orderBy('reg_jns_komitmen.id', 'reg_kom_tindakan.id'),
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'model_jenis' => $model_jenis,
            'model_komitmen' => $model_komitmen,
        ]);
    }

    public function actionViewapprove($id)
    {
        $model = $this->findModelKomitmen($id);

        return $this->render('view_approve', [
            'model' => $model,
        ]);
    }

    public function actionDetailapprove($id)
    {
        $model = $this->findModel($id);
        $model_jenis = $model->jenisKomitmen;

        $model_komitmen = new ActiveDataProvider([
            'query' => Regkomtindakan::find()
                        ->joinWith('jnsKomitmen')
                        ->joinWith('jnsKomitmen.komitmen')
                        ->where(['registrasi_komitmen.id' => $model->id])
                        ->orderBy('reg_jns_komitmen.id','reg_kom_tindakan.id'),
            'pagination' => false,
            'sort' => false,
        ]);

        if ($this->request->post() && $model->load($this->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $status = Options::find()->where(['table' => 'komitmen_reg'])->andWhere(['deskripsi' => 'open'])->one();
                $model->id_status = $status->id;
                $model->approve_manager = new Expression('NOW()');
                $success = $model->save();

                if ($success) {
                    $model_komitmen = Regkomtindakan::find()
                                ->joinWith('jnsKomitmen.komitmen')
                                ->where(['registrasi_komitmen.id' => $model->id])
                                ->all();
                    foreach ($model_komitmen as $komitmen) {
                        $komitmen->status = 'open';
                        $komitmen->status_pic = 'open';
                        if (!($success = $komitmen->save(false))) {
                            break;
                        }
                    }
                }

                if ($success) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Komitmen telah di approve dan dikirim ke PIC terkait.');
                    return $this->redirect(['indexmanager']);
                }
            } catch (Exception $th) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Pleace check your transaction function !.'.$th->getMessage());
            }
        }

        return $this->render('manager/view', [
            'model' => $model,
            'model_jenis' => $model_jenis,
            'model_komitmen' => $model_komitmen,
        ]);
    }

    /**
     * Displays a single Registrasikomitmen model dan the relation model from Registrasikomitmen model.
     * Existing Regjnskomitmen model and Regkomtindakan model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewrevisi($id)
    {
        $model = $this->findModel($id);
        $model_jenis = $model->jenisKomitmen;

        $model_komitmen = new ActiveDataProvider([
            'query' => Regkomtindakan::find()
                        ->join('JOIN', 'reg_jns_komitmen', 'reg_jns_komitmen.id = reg_kom_tindakan.id_jns_komitmen')
                        ->join('JOIN', 'registrasi_komitmen', 'registrasi_komitmen.id = reg_jns_komitmen.id_komitmen')
                        ->where('registrasi_komitmen.id = '.$model->id)
                        ->orderBy('reg_jns_komitmen.id', 'reg_kom_tindakan.id'),
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('revisi/view', [
            'model' => $model,
            'model_komitmen' => $model_komitmen,
        ]);
    }

    /**
     * Update a existing Registrasikomitmen models.
     * For revisi komitmen from RnsD manager to Registrasi staff.
     * If update successfull, the browser will be redirected to the 'indexmanager' page.
     * @param $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model canot be found
     */
    public function actionRevisi($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Registrasikomitmen::SCENARIO_REVISI;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $status = Options::find()->where(['table' => 'komitmen_reg'])->andWhere(['deskripsi' => 'revisi'])->one();
            $model->id_status = $status->id;
            $model->rnd_manager = null;
            $model->approve_manager = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen update status "Revisi" dan dikembalikan ke registrasi staff');
                return $this->redirect(['indexmanager']);
            }
        }

        return $this->render('manager/revisi', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Registrasikomitmen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Registrasikomitmen();
        $model_jenis = [new Regjnskomitmen];
        $model_komitmen = [[new Regkomtindakan]];

        // Data choice for the ActiveForm
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi' OR status = 'Belum-Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $jenis_komitmen = Masterjeniskomitmen::find()->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model_jenis = Model::createMultiple(Regjnskomitmen::className());
                Model::loadMultiple($model_jenis, Yii::$app->request->post());

                $valid = $model->validate();
                $valid = Model::validateMultiple($model_jenis) && $valid;

                if (isset($_POST['Regkomtindakan'][0][0])) {
                    foreach ($_POST['Regkomtindakan'] as $indexProses => $kom) {
                        foreach ($kom as $i => $komitmen) {
                            $data['Regkomtindakan'] = $komitmen;
                            $data_komitmen = new Regkomtindakan;
                            $data_komitmen->load($data);
                            $model_komitmen[$indexProses][$i] = $data_komitmen;
                            $valid = $data_komitmen->validate();
                        }
                    }
                }

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $sql_nama = "SELECT nama_produk FROM tbl_produk WHERE id=:id";
                        $id_obat = $model->id_obat;
                        $obat = $connection->createCommand($sql_nama)->bindParam(':id', $id_obat)->queryOne();
                        
                        $model->id_transaksi = $model->generateKode();
                        $model->nama_obat = $obat['nama_produk'];
                        $model->tanggal_pembuatan = new Expression('NOW()');
                        $status = Options::find()->where(['table' => 'komitmen_reg'])->andWhere(['deskripsi' => 'draft'])->one();
                        $model->id_status = $status->id;
                        $success = $model->save();

                        if ($success) {
                            foreach ($model_jenis as $i => $jenis) {
                                if ($success === false) {
                                    break;
                                }

                                $jenis->id_komitmen = $model->id;
                                $jenis->status = 'open';

                                if (!($success = $jenis->save(false))) {
                                    break;
                                }

                                if (isset($model_komitmen[$i]) && is_array($model_komitmen[$i])) {
                                    foreach ($model_komitmen[$i] as $kom => $komitmen) {
                                        $komitmen->id_jns_komitmen = $jenis->id;
                                        $komitmen->status = 'draft';
                                        if ( ! ($success = $komitmen->save(false))) {
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
                            Yii::$app->session->setFlash('success', 'Data berhasil disimpan dengan status Draft');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('error', 'Error your transaction function. Please check your function'.$e->getMessage());
                        $transaction->rollBack();
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Save list not valid, check your model validation.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'model_jenis' => $model_jenis,
            'model_komitmen' => $model_komitmen,
            'produk' => $produk,
            'pic' => $pic,
            'jenis_komitmen' => $jenis_komitmen,
        ]);
    }

    /**
     * Updates an existing Registrasikomitmen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_jenis = $model->jenisKomitmen;
        $model_komitmen = [];
        $oldKomitmen = [];

        if (!empty($model_jenis)) {
            foreach ($model_jenis as $i => $jenis) {
                $komitmen = $jenis->komitmenTindakan;
                $model_komitmen[$i] = $komitmen;
                $oldKomitmen = ArrayHelper::merge(ArrayHelper::index($komitmen, 'id'), $oldKomitmen);
            }
        }

        // Data choice for the ActiveForm
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $jenis_komitmen = Masterjeniskomitmen::find()->all();

        if ($this->request->isPost && $model->load($this->request->post())) {

            // reset
            $model_komitmen = [];

            $oldJenisId = ArrayHelper::map($model_jenis, 'id', 'id');
            $model_jenis = Model::createMultiple(Regjnskomitmen::className(), $model_jenis);
            Model::loadMultiple($model_jenis, Yii::$app->request->post());
            $deleteJenis = array_diff($oldJenisId, array_filter(ArrayHelper::map($model_jenis, 'id', 'id')));

            // validate
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_jenis) && $valid;

            $komId = [];
            if (isset($_POST['Regkomtindakan'][0][0])) {
                foreach ($_POST['Regkomtindakan'] as $i => $komit) {
                    $komId = ArrayHelper::merge($komId, array_filter(ArrayHelper::getColumn($komit, 'id')));
                    foreach ($komit as $k => $komitmen) {
                        $data['Regkomtindakan'] = $komitmen;
                        $model_komit = (isset($komitmen['id']) && isset($oldKomitmen[$komitmen['id']])) ? $oldKomitmen[$komitmen['id']] : new Regkomtindakan;
                        $model_komit->load($data);
                        $model_komitmen[$i][$k] = $model_komit;
                        $valid = $model_komit->validate();
                    }
                }
            }

            $oldKomId = ArrayHelper::getColumn($oldKomitmen, 'id');
            $deleteKomit = array_diff($oldKomId, $komId);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteKomit)) {
                            Regkomtindakan::deleteAll(['id' => $deleteKomit]);
                        }

                        if (!empty($deleteJenis)) {
                            Regjnskomitmen::deleteAll(['id' => $deleteJenis]);
                        }

                        foreach ($model_jenis as $i => $jenis) {
                            if ($success == false) {
                                break;
                            }

                            $jenis->id_komitmen = $model->id;
                            if (!($success = $jenis->save(false))) {
                                break;
                            }

                            if (isset($model_komitmen[$i]) && is_array($model_komitmen[$i])) {
                                foreach($model_komitmen[$i] as $k => $komitmen) {
                                    $komitmen->id_jns_komitmen = $jenis->id;
                                    if ($komitmen->isNewRecord) {
                                        $komitmen->status = 'draft';
                                    }
                                    if (!($success = $komitmen->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data komitmen berhasil di revisi.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $th) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Function transaction error.'.$th->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_jenis' => $model_jenis,
            'model_komitmen' => $model_komitmen,
            'produk' => $produk,
            'pic' => $pic,
            'jenis_komitmen' => $jenis_komitmen,
        ]);
    }

    /**
     * Updated an existing Registrasikomitmen model.
     * For revisi from R&D Manager to Registrasi staff r&d.
     * If update is successful, the browser will be redirected to the 'viewrevisi' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdaterevisi($id)
    {
        $model = $this->findModel($id);
        $model_jenis = $model->jenisKomitmen;
        $model_komitmen = [];
        $oldKomitmen = [];

        if (!empty($model_jenis)) {
            foreach ($model_jenis as $i => $jenis) {
                $komitmen = $jenis->komitmenTindakan;
                $model_komitmen[$i] = $komitmen;
                $oldKomitmen = ArrayHelper::merge(ArrayHelper::index($komitmen, 'id'), $oldKomitmen);
            }
        }

        // Data choice for the ActiveForm
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $pic = User::find()
                ->where(['NOT IN', 'level_access', ['administrator', 'manager']])
                ->all();
        $jenis_komitmen = Masterjeniskomitmen::find()->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            // reset
            $model_komitmen = [];

            $oldJenisId = ArrayHelper::map($model_jenis, 'id', 'id');
            $model_jenis = Model::createMultiple(Regjnskomitmen::className(), $model_jenis);
            Model::loadMultiple($model_jenis, Yii::$app->request->post());
            $deleteJenis = array_diff($oldJenisId, array_filter(ArrayHelper::map($model_jenis, 'id', 'id')));

            // validate
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_jenis) && $valid;

            $komId = [];
            if (isset($_POST['Regkomtindakan'][0][0])) {
                foreach ($_POST['Regkomtindakan'] as $i => $komit) {
                    $komId = ArrayHelper::merge($komId, array_filter(ArrayHelper::getColumn($komit, 'id')));
                    foreach ($komit as $k => $komitmen) {
                        $data['Regkomtindakan'] = $komitmen;
                        $model_komit = (isset($komitmen['id']) && isset($oldKomitmen[$komitmen['id']])) ? $oldKomitmen[$komitmen['id']] : new Regkomtindakan;
                        $model_komit->load($data);
                        $model_komitmen[$i][$k] = $model_komit;
                        $valid = $model_komit->validate();
                    }
                }
            }

            $oldKomId = ArrayHelper::getColumn($oldKomitmen, 'id');
            $deleteKomit = array_diff($oldKomId, $komId);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteKomit)) {
                            Regkomtindakan::deleteAll(['id' => $deleteKomit]);
                        }

                        if (!empty($deleteJenis)) {
                            Regjnskomitmen::deleteAll(['id' => $deleteJenis]);
                        }

                        foreach ($model_jenis as $i => $jenis) {
                            if ($success == false) {
                                break;
                            }

                            $jenis->id_komitmen = $model->id;
                            if (!($success = $jenis->save(false))) {
                                break;
                            }

                            if (isset($model_komitmen[$i]) && is_array($model_komitmen[$i])) {
                                foreach($model_komitmen[$i] as $k => $komitmen) {
                                    $komitmen->id_jns_komitmen = $jenis->id;
                                    if ($komitmen->isNewRecord) {
                                        $komitmen->status = 'draft';
                                    }
                                    if (!($success = $komitmen->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data komitmen berhasil di revisi.');
                        return $this->redirect(['viewrevisi', 'id' => $model->id]);
                    }
                } catch (Exception $th) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Function transaction error.'.$th->getMessage());
                }
            }
        }

        return $this->render('revisi/update', [
            'model' => $model,
            'model_jenis' => $model_jenis,
            'model_komitmen' => $model_komitmen,
            'produk' => $produk,
            'pic' => $pic,
            'jenis_komitmen' => $jenis_komitmen,
        ]);
    }

    /**
     * Deletes an existing Registrasikomitmen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Send to RnD Manager and update an existing Registrasikomitmen model.
     * If Update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionKirim($id)
    {
        $model = $this->findModel($id);

        $model->rnd_manager = 3;
        $status = Options::find()->where(['table' => 'komitmen_reg'])->andWhere(['deskripsi' => 'menunggu'])->one();
        $model->id_status = $status->id;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil dikirim ke R&D Manager, silahkan tunggu proses approve');
	    // $this->redirect(['index']);
	    $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Update an existing Regjnskomitmen model.
     * For close the Komitmen by RnD Registrasi staff.
     * If update is successfull, the browser will be redirected to the 'indexbpom' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClosekomitmen($id)
    {
        $model = $this->findJnsKomitmen($id);
        $model_komitmen = $this->findModel($model->id_komitmen);

        $searchDetail = new RegkomtindakanSearch();
        $dataDetail = $searchDetail->searchDetail($this->request->queryParams, $model->id);

        $model->scenario = Regjnskomitmen::SCENARIO_CLOSE;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiran = UploadedFile::getInstance($model, 'surat_pengantar');
            if (!empty($lampiran)) {
                $name_lamp = 'sp_bpom_'.$model->komitmen->nama_obat.'_'.$model->id.'.'.$lampiran->extension;
                $lampiran->saveAs(Yii::getAlias('@lampiran/surat_pengantar/').$name_lamp);
                $model->surat_pengantar = $name_lamp;
            }
            $model->status = 'done';
            $model->tanggal_close = new Expression('NOW()');
            if ($model->save()) {
                $hasil = [];
                foreach ($model_komitmen->jenisKomitmen as $jenis) {
                    if ($jenis->status == 'done') {
                        $hasil[] = 'true';
                    } else {
                        $hasil[] = 'false';
                    }
                }

                if (count(array_unique($hasil)) == 1) {
                    $status = Options::find()->where(['table' => 'komitmen_reg'])->andWhere(['deskripsi' => 'close'])->one();
                    $model_komitmen->id_status = $status->id;
                    $model_komitmen->tanggal_close = new Expression('NOW()');
                    $model_komitmen->save();
                }

                Yii::$app->session->setFlash('success', 'Komitmen telah diselesaikan dan dikirim ke BPOM');
                return $this->redirect(['indexbpom']); 
            }           
        }

        return $this->render('bpom/close', [
            'model' => $model,
            'searchDetail' => $searchDetail,
            'dataDetail' => $dataDetail,
        ]);
    }

    /**
     * Finds the Registrasikomitmen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Registrasikomitmen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Registrasikomitmen::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findJnsKomitmen($id)
    {
        if (($model = Regjnskomitmen::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requeset page does not exist.');
    }

    protected function findModelKomitmen($id)
    {
        if (($model = Regkomtindakan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

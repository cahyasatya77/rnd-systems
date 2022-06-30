<?php

namespace backend\controllers;

use common\models\Meetingjustifikasi;
use backend\models\MeetingjustifikasiSearch;
use backend\models\Model;
use backend\models\Modelstatis;
use common\models\Justifikasidetail;
use common\models\Logjustifikasimeeting;
use common\models\Meetingdokumen;
use common\models\Meetingjustifikasidetail;
use common\models\Meetingkategori;
use common\models\Meetingregistrasi;
use common\models\User;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * MeetingjustifikasiController implements the CRUD actions for Meetingjustifikasi model.
 */
class MeetingjustifikasiController extends Controller
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
                                'index', 'firstcreate', 'create', 'update', 'view',
                                'variasi', 'deadline', 'kirim', 'dokumen', 'delete'
                            ],
                            'allow' => true,
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexapprovepic', 'approvepic', 'revisipic'],
                            'allow' => Yii::$app->user->level == 'manager',
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexreview', 'approvernd', 'revisirnd'],
                            'allow' => (Yii::$app->user->idDept == 'D0001' and Yii::$app->user->idSection == 'D0001SC001'),
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexreviewmanager', 'approverndmanager', 'revisimanager'],
                            'allow' => (Yii::$app->user->idDept == 'D0001' and Yii::$app->user->level == 'manager'),
                            'roles' => ['@']
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Get data dokumen
     */
    public function actionDeadline($kode)
    {
        $model = Meetingdokumen::findOne($kode);

        return Json::encode($model);
    }

    /**
     * Get data Variasi
     */
    public function actionVariasi($id)
    {
        $user_dept = User::findOne(Yii::$app->user->id);
        $model = Meetingkategori::find()
                    ->joinWith('meetingdokumen')
                    ->joinWith('meetingdokumen.user')
                    ->where(['id_meeting' => $id])
                    ->andWhere(['OR', 
                        ['meeting_dokumen.status_pic' => 'open'],
                        ['meeting_dokumen.status_pic' => 'revisi']
                    ])
                    ->andWhere(['user.id_dept' => $user_dept->id_dept])
                    ->all();

        if (count($model) > 0) {
            foreach ($model as $variasi) {
                echo "<option value='".$variasi['id']."'>".$variasi['jenisMeeting']['deskripsi']." : ".$variasi['deskripsi']."</option>";
            }
        } else {
            echo "<option></option>";
        }
    }

    /**
     * Get data dokumen
     */
    public function actionDokumen($id)
    {
        $user_dept = User::findOne(Yii::$app->user->id);
        $model = Meetingdokumen::find()
            ->joinWith('user')
            ->where(['id_kategori' => $id])
            ->andWhere(['OR', 
                ['status_pic' => 'open'],
                ['status_pic' => 'revisi']
            ])
            ->andWhere(['user.id_dept' => $user_dept->id_dept])
            ->all();
        if (count($model) > 0) {
            foreach ($model as $dokumen) {
                echo "<option value='".$dokumen['id']."'>".$dokumen['dokumen']."</option>";
            }
        } else {
            echo "<option></option>";
        }
    }

    /**
     * Lists all Meetingjustifikasi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dept = Yii::$app->user->idDept;

        $searchModel = new MeetingjustifikasiSearch();
        // $dataProvider = $searchModel->search($this->request->queryParams);
        // data provider for index `Draft`
        $dataDraft = $searchModel->searchDraft($this->request->queryParams, $dept);
        // data provider for index `Revisi`
        $dataRevisi = $searchModel->searchRevisi($this->request->queryParams, $dept);
        // data provider for index waiting approve
        $dataWaiting = $searchModel->searchWaiting($this->request->queryParams, $dept);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataDraft' => $dataDraft,
            'dataRevisi' => $dataRevisi,
            'dataWaiting' => $dataWaiting,
        ]);
    }

    public function actionIndexapprovepic()
    {
        $user = Yii::$app->user->id;

        $searchModel = new MeetingjustifikasiSearch();
        $dataProvider = $searchModel->saerchKirim($this->request->queryParams, $user);

        return $this->render('manager_pic/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List all Meetingjustifikasi models.
     * With status review rnd
     * @return string
     */
    public function actionIndexreview()
    {
        $searchModel = new MeetingjustifikasiSearch();
        $dataProvider = $searchModel->searchReview($this->request->queryParams);

        return $this->render('rnd/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List all Meetingjustifikasi models.
     * With status review rnd manager.
     * @return string
     */
    public function actionIndexreviewmanager()
    {
        $searchModel = new MeetingjustifikasiSearch();
        $dataProvider = $searchModel->searchReviewManager($this->request->queryParams);

        return $this->render('rnd_manager/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Meetingjustifikasi model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $meeting = $this->findMeeting($model->id_registrasi);
        $kategori = $this->findKategori($model->id_kategori);

        $query = Meetingjustifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'meeting' => $meeting,
            'kategori' => $kategori,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Meetingjustifikasi model for choose condition ID registrasi and kategori.
     * If creation is successful, the browser will bw redirected to the `create` page.
     * @return string|\yii\web\Response
     */
    public function actionFirstcreate()
    {
        $model = new Modelstatis();

        $user_dept = User::findOne(Yii::$app->user->id);

        $produk = Meetingregistrasi::find()
                ->select(['meeting_registrasi.id', 'CONCAT(id_transaksi, " / ", nama_produk) as item'])
                ->joinWith('meetingkategori')
                ->joinWith('meetingkategori.meetingdokumen')
                ->joinWith('meetingkategori.meetingdokumen.user')
                ->where(['OR', 
                    ['meeting_dokumen.status_pic' => 'open'],
                    ['meeting_dokumen.status_pic' => 'revisi']
                ])
                ->andWhere(['user.id' => Yii::$app->user->id])
                ->asArray()
                ->all();

        $model->scenario = Modelstatis::SCENARIO_JUSTIF_MEETING;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $id = $model->id_meeting;
                $kat = $model->variasi;
                $dok = $model->tindakan;
                return $this->redirect(['create', 'id' => $id, 'kat' => $kat, 'dok' => $dok]);
            }
        }

        return $this->render('first_create', [
            'model' => $model,
            'produk' => $produk,
        ]);
    }

    /**
     * Creates a new Meetingjustifikasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id, $kat, $dok)
    {
        $model = new Meetingjustifikasi();
        $model_detail = [new Meetingjustifikasidetail];
        $model_meeting = $this->findMeeting($id);
        $model_kategori = $this->findKategori($kat);
        $model_dokumen = $this->findDokumen($dok);

        // List items 
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();
        $data_dokumen = Meetingdokumen::find()->where(['id_kategori' => $model_kategori->id])->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model_detail = Model::createMultiple(Meetingjustifikasidetail::className());
                Model::loadMultiple($model_detail, Yii::$app->request->post());

                $valid = $model->validate();
                $valid = Model::validateMultiple($model_detail) && $valid;

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $model->id_registrasi = $model_meeting->id;
                        $model->id_kategori = $model_kategori->id;
                        $model->id_status = 1;
                        $success = $model->save();

                        if ($success) {
                            foreach ($model_detail as $detail) {
                                $detail->id_justifikasi = $model->id;

                                if (!($success = $detail->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        if ($success) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Justifikasi berhasil disimpan dengan status `Draft`.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $th) {
                        Yii::$app->session->setFlash('error', 'Error your transaction function. Pleace check your function'.$th->getMessage());
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
            'model_detail' => $model_detail,
            'model_meeting' => $model_meeting,
            'model_kategori' => $model_kategori,
            'data_dokumen' => $data_dokumen,
            'user_pic' => $user_pic,
            'model_dokumen' => $model_dokumen,
        ]);
    }

    /**
     * Updates an existing Meetingjustifikasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_detail = $model->details;

        $model_meeting = $this->findMeeting($model->id_registrasi);
        $model_kategori = $this->findKategori($model->id_kategori);

        // List items 
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();
        $data_dokumen = Meetingdokumen::find()->where(['id_kategori' => $model_kategori->id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $idOld = ArrayHelper::map($model_detail, 'id', 'id');
            $model_detail = Model::createMultiple(Meetingjustifikasidetail::className(), $model_detail);
            Model::loadMultiple($model_detail, Yii::$app->request->post());
            $deleteOld = array_diff($idOld, array_filter(ArrayHelper::map($model_detail, 'id', 'id')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($model_detail) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $success = $model->save(false);
                    if ($success) {
                        if (!empty($deleteOld)) {
                            Justifikasidetail::deleteAll(['id' => $deleteOld]);
                        }

                        foreach ($model_detail as $detail) {
                            $detail->id_justifikasi = $model->id;
                            if (!($success = $detail->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        if ($success) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Successful, change update data justifikasi.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                } catch (Exception $th) {
                    Yii::$app->session->setFlash('error', 'Error your transaction function. Please check your function');
                    $transaction->rollBack();
                }
            } else {
                Yii::$app->session->setFlash('error', 'Function not valid, pleace check your model.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_detail' => $model_detail,
            'model_meeting' => $model_meeting,
            'model_kategori' => $model_kategori,
            'user_pic' => $user_pic,
            'data_dokumen' => $data_dokumen,
        ]);
    }

    /**
     * Send justifikasi to the manager PIC
     */
    public function actionKirim($id)
    {
        $model = $this->findModel($id);
        $model->id_status = 3;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Justifikasi dikirim ke Manager terkait untuk dilakukan approve. Check the waiting list index !.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Approve manager PIC
     */
    public function actionApprovepic($id)
    {
        $model = $this->findModel($id);
        $meeting = Meetingregistrasi::findOne($model->id_registrasi);
        $kategori = Meetingkategori::findOne($model->id_kategori);

        $query = Meetingjustifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->id_status = 4;
            $model->pic_manager_approve = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi approve, dikirim ke rnd department untuk direview');
                return $this->redirect(['indexapprovepic']);
            }
        }

        return $this->render('manager_pic/approve', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'meeting' => $meeting,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Revisi PIC Manager
     */
    public function actionRevisipic($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Meetingjustifikasi::SCENARIO_REVISI_PIC;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->pic_manager_approve = null;
            $model->id_status = 2;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi dikirimkan kembali ke PIC terkiait dengan status `Revisi`');
                return $this->redirect(['indexapprovepic']);
            }
        }

        return $this->render('manager_pic/revisi', [
            'model' => $model
        ]);
    }

    /**
     * Approve and Review R&D Registrasi
     */
    public function actionApprovernd($id)
    {
        $model = $this->findModel($id);
        $meeting = Meetingregistrasi::findOne($model->id_registrasi);
        $kategori = Meetingkategori::findOne($model->id_kategori);

        $query = Meetingjustifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->id_status = 5;
            $model->rnd_approve = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi approve, dikirim ke R&D Manager untuk di review.');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('rnd/approve', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'meeting' => $meeting,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Revisi RnD Registrasi
     */
    public function actionRevisirnd($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Meetingjustifikasi::SCENARIO_REVISI_RND;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->id_status = 2;
            $model->pic_manager_approve = null;
            $model->rnd_approve = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi dikirimkan kembali ke PIC terkait dengan status Revisi');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('rnd/revisi', [
            'model' => $model,
        ]);
    }

    /**
     * Approve and Review R&D Manager
     */
    public function actionApproverndmanager($id)
    {
        $model = $this->findModel($id);
        $meeting = Meetingregistrasi::findOne($model->id_registrasi);
        $kategori = Meetingkategori::findOne($model->id_kategori);

        $query = Meetingjustifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // code perubahan tanggal timeline tindakan dokumen
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->id_status = 6;
                $model->rnd_manager_approve = new Expression('NOW()');
                $success = $model->save();

                if ($success) {
                    foreach ($model->details as $detail) {
                        $dokumen = Meetingdokumen::findOne($detail->id_dokumen);
                        $dokumen->deadline = $detail->deadline_new;
                        if (! ($success = $dokumen->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }

                if ($success) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Justifikasi telah di approve, deadline pada tindakan telah dilakukan perubahan dengan deadline baru.');
                    return $this->redirect(['indexreviewmanager']);
                }
            } catch (Exception $th) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Pleace check your function transaction').$th->getMessage();
            }
        }

        return $this->render('rnd_manager/approve', [
            'model' => $model,
            'meeting' => $meeting,
            'kategori' => $kategori,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Revisi R&D Manager
     */
    public function actionRevisimanager($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Meetingjustifikasi::SCENARIO_REVISI_MANAGER;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->id_status = 2;
            $model->pic_manager_approve = null;
            $model->rnd_approve = null;
            $model->rnd_manager_approve = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi dikirimkan kembali ke PIC terkait dengan status Revisi');
                return $this->redirect(['indexreviewmanager']);
            }
        }

        return $this->render('rnd_manager/revisi', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Meetingjustifikasi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $detail = $model->details;
        $idDetail = [];
        foreach ($detail as $list) {
            $idDetail[] = $list->id;
        }

        // Insert to log justifikasi dokumen meeting
        $model_log = new Logjustifikasimeeting();
        $model_log->id_meeting = $model->id_registrasi;
        $model_log->id_kategori = $model->id_kategori;
        $model_log->id_dokumen = implode(',', $idDetail);
        $model_log->alasan_justif = $model->alasan_justif;
        $model_log->create_justif = $model->created_by;
        $model_log->pic_manager = $model->pic_manager;
        $model_log->pic_manager_approve = $model->pic_manager_approve;
        $model_log->rnd_approve = $model->rnd_approve;
        $model_log->rnd_approve_manager = $model->rnd_manager_approve;
        $model_log->note_revisi = $model->revisi_pic_manager;
        $model_log->revisi_rnd = $model->revisi_rnd;
        $model_log->revisi_rnd_manager = $model->revisi_rnd_manager;
        $model_log->justif_delete = new Expression('NOW()');
        // print_r($model_log);
        // die();

        if ($model_log->save()) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Justifikasi berhasil dihapus atau dibatalkan.');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', $model_log->errors);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Meetingjustifikasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Meetingjustifikasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meetingjustifikasi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findMeeting($id)
    {
        if (($model = Meetingregistrasi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findKategori($id)
    {
        if (($model = Meetingkategori::findOne(['id' => $id])) !== null) {
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

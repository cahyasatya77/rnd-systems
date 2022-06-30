<?php

namespace backend\controllers;

use backend\models\TagihanmeetingSearch;
use common\models\Meetingdokumen;
use common\models\Meetingkategori;
use common\models\Meetingregistrasi;
use common\models\User;
use Yii;
use Exception;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\AccessController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


/**
 * TagihanmeetingController implements the CRUD action for fullfillment assignment to PIC from Meetingdokumen model.
 */
class TagihanmeetingController extends Controller
{
    /**
     * @inheritdoc
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
                            'actions' => [
                                'index', 'tindakan', 'kirim', 'download', 'deletedok', 
                                'indexrevisipic', 'updaterevisi', 'deletedokrevisi', 'kirimrevisi'
                            ],
                            'allow' => true,
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexapprove', 'approve', 'revisi'],
                            'allow' => Yii::$app->user->level == 'manager',
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexreview', 'reviewrnd', 'revisirnd'],
                            'allow' => Yii::$app->user->idDept == 'D0001',
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['indexreviewrndmanager', 'reviewrndmanager', 'revisirndmanager'],
                            'allow' => ((Yii::$app->user->idDept == 'D0001') and (Yii::$app->user->level == 'manager')),
                            'roles' => ['@'],
                        ],
                    ], 
                ],
            ],
        );
    }

    /**
     * List all Meetingdokumen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagihanmeetingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();

        return $this->render('pic/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
        ]);
    }

    /**
     * List only status pic `review manager` Meetingdokumen model.
     * @return miixed
     */
    public function actionIndexapprove()
    {
        $searchModel = new TagihanmeetingSearch();
        $dataProvider = $searchModel->searchApprove($this->request->queryParams);

        return $this->render('manager_pic/indexapprove', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List only status pic `approve` Meetingdokumen model.
     * And status komitmen `review rnd` Meetingdokumen model.
     * @return mixed
     */
    public function actionIndexreview()
    {
        $searchModel = new TagihanmeetingSearch();
        $dataProvider = $searchModel->searchReview($this->request->queryParams);

        return $this->render('rnd/indexreview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List only status pic `approve` Meetingdokumen model.
     * And status komitmen `review rnd manager` Meetingdokumen model.
     * @return mixed
     */
    public function actionIndexreviewrndmanager()
    {
        $searchModel = new TagihanmeetingSearch();
        $dataProvider = $searchModel->searchReviewRndManager($this->request->queryParams);

        return $this->render('manager_rnd/indexreview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List only status pic 'revisi' Meeting dokumen model.
     * @return mixed
     */
    public function actionIndexrevisipic()
    {
        $searchModel = new TagihanmeetingSearch();
        $dataProvider = $searchModel->searchRevisi($this->request->queryParams);

        return $this->render('pic/indexrevisipic', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updated an existing Meetingdokumen model.
     * For approve dokumen submit to manager pic.
     * If update is successful, the browser will be redirected to 'indexapprove' page.
     * @param int $id Id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApprove($id)
    {
        $model = $this->findDokumen($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status_pic = 'approve';
            $model->status = 'review rnd';
            $model->pic_manager_approve = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Dokumen meeting telah dilakukan approve dan akan dilakukan review oleh R&D Registrasi');
                return $this->redirect(['indexapprove']);
            }
        }

        return $this->render('manager_pic/approve', [
            'model' => $model,
        ]);
    }

    /**
     * Updated and Review on existing Meetingdokumen model.
     * If Update is successful, the browser will be redirected to the 'indexreview' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReviewrnd($id)
    {
        $model = $this->findDokumen($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'review rnd manager';
            $model->registrasi_approve = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Dokumen talah di approve dan dikirim ke R&D manager untuk dilakukan review ulang.');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('rnd/review', [
            'model' => $model
        ]);
    }

    /**
     * Updated an existing Meetingdokumen model.
     * For approve document and closed the kategory meeting is 'kategori variasi' from manager RnD.
     * if update is successful, the model Meetingdokumen foreach for the result this Meetingkategori can be send to Aero BPOM.
     * and the browser will be redirected to the 'indexreviewrndmanager' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReviewrndmanager($id)
    {
        $model = $this->findDokumen($id);
        $model_kategori = $this->findKategori($model->id_kategori);
        $model_meeting = $this->findMeeting($model_kategori->id_meeting);
        // all kategori
        $all_kategori = Meetingkategori::find()
            ->where(['id_meeting' => $model_meeting->id])
            ->andWhere(['id_jenis_meeting' => 1])
            ->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'done';
            $model->manager_rnd_approve = new Expression('NOW()');
            if ($model->save()) {
                $dokumenTindakan = Meetingdokumen::find()
                        ->joinWith('kategori')
                        ->joinWith('kategori.meeting')
                        ->where(['meeting_kategori.id_jenis_meeting' => 1])
                        ->andWhere(['meeting_registrasi.id' => $model_meeting->id])
                        ->all();
                $hasil = [];
                foreach ($dokumenTindakan as $dok) {
                    if ($dok->status == 'done') {
                        $hasil[] = 'true';
                    } else {
                        $hasil[] = 'false';
                    }
                }
                if (count(array_unique($hasil)) == 1) {
                    foreach ($all_kategori as $kate) {
                        $kate->status = 'approve';
                        $kate->save();
                    }
                    // updatte status done model meeting
                    $model_meeting->id_status = 17;
                    $model_meeting->save();
                }

                Yii::$app->session->setFlash('success', 'Notulen pada meeting telah disetujui jika semua dokumen pada kategori variasi terpenuhi Registrasi akan melakukan close');
                return $this->redirect(['indexreviewrndmanager']);
            }
        }

        return $this->render('manager_rnd/review', [
            'model' => $model,
            'model_kategori' => $model_kategori,
        ]);
    }

    /**
     * Update an existing Meetingdokumen model.
     * For revisi dokumen from manager PIC to pic dokumen.
     * If update is successful, the browser will be redirected to the 'indexapprove' page.
     * @param int $id ID
     * @return mixed
     * @throws NotfoundHttpException if the model cannot be found
     */
    public function actionRevisi($id)
    {
        $model = $this->findDokumen($id);
        $model->scenario = Meetingdokumen::SCENARIO_REVISI;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status_pic = 'revisi';
            // $model->pic_manager = null;
            $model->pic_manager_approve = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Tidakan dokumen dilakukan revisi dan dikembalikan ke PIC terkait');
                return $this->redirect(['indexapprove']);
            }
        }

        return $this->render('manager_pic/revisi', [
            'model' => $model,
        ]);
    }

    public function actionRevisirnd($id)
    {
        $model = $this->findDokumen($id);
        $model->scenario = Meetingdokumen::SCENARIO_REVISI_REG;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'open';
            $model->status_pic = 'revisi';
            $model->pic_manager_approve = null;
            $model->registrasi_approve = null;
            $model->manager_rnd_approve = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Dokumen dilakukan revisi dan dikembalikan ke PIC terkait.');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('rnd/revisi', [
            'model' => $model,
        ]);
    }

    public function actionRevisirndmanager($id)
    {
        $model = $this->findDokumen($id);
        $model->scenario = Meetingdokumen::SCENARIO_REVISI_MNG_RND;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'open';
            $model->status_pic = 'revisi';
            $model->pic_manager_approve = null;
            $model->registrasi_approve = null;
            $model->manager_rnd_approve = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Dokumen dilakukan revisi dan dikembalikan ke PIC terkait.');
                return $this->redirect(['indexreviewrndmanager']);
            }
        }

        return $this->render('manager_rnd/revisi', [
            'model' => $model,
        ]);
    }

    /**
     * Updated an existinf Meetingdokumen model.
     * For submission tindakan upload document from user PIC.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTindakan($id)
    {
        $model = $this->findDokumen($id);
        $kategori = $model->kategori->jenisMeeting->deskripsi;

        // dropdown PIC
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();

        // use scenario
        $model->scenario = Meetingdokumen::SCENARIO_TINDAKAN;

        if ($this->request->isPost && $model->load($this->request->post())) {
            
            $lampiran = UploadedFile::getInstance($model, 'nama_dok');
            if (!empty($lampiran)) {
                $name = 'Lampiran_meeting_'.$model->id.'.'.$lampiran->extension;
                $lampiran->saveAs(Yii::getAlias('@lampiran/meeting/').$name);
                $model->nama_dok = $name;
            }

            $model->status_pic = 'draft';
            $model->tanggal_submit = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Tagihan dokumen tersimpan, status masih dalam draft. Silahkan kirim ke pic manager.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('pic/tindakan', [
            'model' => $model,
            'user_pic' => $user_pic,
        ]);
    }

    /**
     * Updated an existing Meetingdokumen model.
     * For submission revisi tindakan dokumen from user PIC.
     * If update is successful, the browser will be redirected to the 'indexrevisi' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdaterevisi($id)
    {
        $model = $this->findDokumen($id);
        $kategori = $model->kategori->jenisMeeting->deskripsi;

        // dropdown PIC
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();

        // use scenario
        $model->scenario = Meetingdokumen::SCENARIO_TINDAKAN;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiran = UploadedFile::getInstance($model, 'nama_dok');
            if (!empty($lampiran)) {
                $name = 'Lampiran_meeting_'.$model->id.'.'.$lampiran->extension;
                $lampiran->saveAs(Yii::getAlias('@lampiran/meeting/').$name);
                $model->nama_dok = $name;
            }

            $model->tanggal_submit = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Tagihan dokumen tersimpan, status masih dalam draft. Silahkan kirim ke pic manager.');
                return $this->redirect(['indexrevisipic']);
            }
        }

        return $this->render('pic/update_revisi', [
            'model' => $model,
            'user_pic' => $user_pic,
        ]);
    }

    /**
     * Updated an existing Meetingdokumen model.
     * For submission tindakan dokumen from pic to manager pic.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionKirim($id)
    {
        $model = $this->findDokumen($id);
        $model->status_pic = 'review manager';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Pemenuhan dokumen telah dikirim ke pic manager, dalam proses review');
            $this->redirect(['index']);
        }
    }

    public function actionKirimrevisi($id)
    {
        $model = $this->findDokumen($id);
        if ($model->pic_manager == null) {
            Yii::$app->session->setFlash('error', 'Pic manager is null');
            $this->redirect(['indexrevisipic']);
        }

        $model->status_pic = 'review manager';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Pemenuhan dokumen telah dikirim ke pic manager, dalam proses review');
            $this->redirect(['indexrevisipic']);
        }
    }

    /**
     * Download lampiran dokumen from Meetingdokumen model.
     * 
     * @param $id ID
     */
    public function actionDownload($id)
    {
        $model = $this->findDokumen($id);
        $path = Yii::getAlias('@lampiran/meeting/').$model->nama_dok;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, $model->nama_dok);
        }
    }

    /**
     * Deletes an existing file in Meetingdokumen model and delete on directory file.
     * If delete is successful, the browser will be redirected to the 'tindakan' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletedok($id)
    {
        $model = $this->findDokumen($id);
        $filePath = Yii::getAlias('@lampiran/meeting/').$model->nama_dok;
        $deleteFile = unlink($filePath);
        if ($deleteFile) {
            $model->nama_dok = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Lampiran dokumen berhasil dihapus');
                return $this->redirect(['tindakan', 'id' => $model->id]);
            }
        }
    }

    public function actionDeletedokrevisi($id)
    {
        $model = $this->findDokumen($id);
        $filePath = Yii::getAlias('@lampiran/meeting/').$model->nama_dok;
        $deleteFile = unlink($filePath);
        if ($deleteFile) {
            $model->nama_dok = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Lampiran dokumen berhasil dihapus');
                return $this->redirect(['updaterevisi', 'id' => $model->id]);
            }
        }
    }

    /**
     * Find the Meetingdokumen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID.
     * @return Meetingdokumen the load model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDokumen($id)
    {
        if (($model = Meetingdokumen::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Find the Meetingkategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID.
     * @return Meetingdokumen the load model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findKategori($id)
    {
        if (($model = Meetingkategori::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The request page does not exist.');
    }

    /**
     * Find the Meetingregistrasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID.
     * @return Meetingregistrasi the load model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findMeeting($id)
    {
        if (($model = Meetingregistrasi::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The request page does not exist.');
    }
} 

<?php

namespace backend\controllers;

use backend\models\TagihanSearch;
use common\models\Regjnskomitmen;
use common\models\Regkomtindakan;
use common\models\User;
use Yii;
use Exception;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * TagihanController implements the CRUD action for fullfillment assignment to PIC from Regkomtindakan model.
 */
class TagihanController extends Controller
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
                    ]
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'tindakan', 'kirim', 'indexrevisipic',
                                'updaterevisi', 'kirimrevisi', 'download', 'deletedok'
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
                            'actions' => ['indexreviewrndmanager', 'approverndmanager'],
                            'allow' => ((Yii::$app->user->idDept == 'D0001') and (Yii::$app->user->level == 'manager')),
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ],
        );
    }

    /**
     * List all Regkomtindakan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagihanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
        ]);
    }

    /**
     * List only status pic `review manager` Regkotindakan models.
     * @return mixed
     */
    public function actionIndexapprove()
    {
        $searchModel = new TagihanSearch();
        $dataProvider = $searchModel->searchApprove($this->request->queryParams);

        return $this->render('indexapprove', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /** 
     * List only status pic `revisi` Regkomtindakan models.
     * @return mixed
     */
    public function actionIndexrevisipic()
    {
        $searchModel = new TagihanSearch();
        $dataProvider = $searchModel->searchRevisi($this->request->queryParams);

        return $this->render('indexrevisipic', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List only status pic `approve` Regkomtindakan model.
     * And status komitmen `review rnd` Registrasikomitmen model.
     * @return mixed
     */
    public function actionIndexreview()
    {
        $searchModel = new TagihanSearch();
        $dataProvider = $searchModel->searchReview($this->request->queryParams);

        return $this->render('indexreview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List only status pic `approve` Regkomtindakan model.
     * And status komitmen `review rnd manager` Registrasikomitmen model.
     * @return mixed
     */
    public function actionIndexreviewrndmanager()
    {
        $searchModel = new TagihanSearch();
        $dataProvider = $searchModel->searchReviewRndManager($this->request->queryParams);

        return $this->render('indexreview_rndmanager', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updated an existing Regkomtindakan model.
     * For approve komitmen to manager pic.
     * if update is successful, the browser will be redirected to 'indexapprove' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApprove($id)
    {
        $model = $this->findModelKomitmen($id);
        
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status_pic = 'approve';
            $model->status = 'review rnd';
            $model->app_manager_pic = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen telah dilakukan approve dan akan dilakukan review oleh R&D Registrasi');
                return $this->redirect(['indexapprove']);
            }
        }

        return $this->render('approve', [
            'model' => $model
        ]);
    }

    /**
     * Updated an existing Regkomtindakan model.
     * For approve komitmen and closed the komitmen from manager RnD.
     * if upfate is successful, the model Regjnskomitmen foreach for the result this jnsKomitmen can be send to BPOM.
     * and the browser will be redirected to the 'indexreviewrndmanager' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApproverndmanager($id)
    {
        $model = $this->findModelKomitmen($id);
        $model_jenis = $this->findJenisKomitmen($model->id_jns_komitmen);
        
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'done';
            $model->approve_mng_rnd = new Expression('NOW()');
            if ($model->save()) {
                $komitTindakan = Regkomtindakan::find()
                        ->where(['jenis_dokumen' => 'Dokumen Submit'])
                        ->andWhere(['id_jns_komitmen' => $model_jenis->id])
                        ->andWhere(['status' => 'done'])
                        ->all();
                if ($komitTindakan != null) {
                    $hasil = [];
                    foreach ($komitTindakan as $komit) {
                        if ($komit->status == 'done') {
                            $hasil[] = 'true';
                        } else {
                            $hasil[] = 'false';
                        }
                    }
                    if (count(array_unique($hasil)) == 1) {
                        $model_jenis->status = 'approve';
                        $model_jenis->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Komitmen telah dilakukan close');
                return $this->redirect(['indexreviewrndmanager']);
            }
        }

        return $this->render('approve_rnd_manager', [
            'model' => $model,
        ]);
    }

    /**
     * Updated an existing Regkomtindakan model.
     * For revisi komitmen from manager pic to pic komitmen.
     * If update is successful, the browser will be redirected to 'indexapprove' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRevisi($id)
    {
        $model = $this->findModelKomitmen($id);
        $model->scenario = Regkomtindakan::SCENARIO_REVISI;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status_pic = 'revisi';
            $model->app_manager_pic = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen tindakan dilakukan revisi dan dikembalikan ke PIC terkait');
                return $this->redirect(['indexapprove']);
            }
        }

        return $this->render('revisi', [
            'model' => $model,
        ]);
    }

    public function actionRevisirnd($id)
    {
        $model = $this->findModelKomitmen($id);
        $model->scenario = Regkomtindakan::SCENARIO_REVISI_RND;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'open';
            $model->status_pic = 'revisi';
            $model->app_manager_pic = null;
            $model->approve_rnd = null;
            $model->approve_mng_rnd = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen tindakan dilakukan revisi dan dikembalikan ke PIC terkait');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('revisi_rnd', [
            'model' => $model,
        ]);
    }

    /**
     * Updated and Review on existing Regkomtindakan model.
     * If Update is successful, the browser will be redirected to the 'indexreview' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReviewrnd($id)
    {
        $model = $this->findModelKomitmen($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 'review rnd manager';
            $model->approve_rnd = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen talah di approve dan dikirim ke R&D manager untuk dilakukan review ulang.');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('reviewrnd', [
            'model' => $model,
        ]);
    }

    /**
     * Updated an existing Regkomtindakan model.
     * For submission tindakan komitmen from user PIC.
     * If update is successful, the browser will be redirected to 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTindakan($id)
    {
        $model = $this->findModelKomitmen($id);
        $jenis = $model->jnsKomitmen->jenis->jenis;

        // dropdown PIC
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();

        // use scenarios
        $model->scenario = Regkomtindakan::SCENARIO_TINDAKAN;

        if ($this->request->isPost && $model->load($this->request->post())) {
            
            $lampiran = UploadedFile::getInstance($model, 'nama_dok');
            if (!empty($lampiran)) {
                $lampiran->saveAs(Yii::getAlias('@lampiran/komitmen/').'Lampiran_'.$model->id.'.'.$lampiran->extension);
                $model->nama_dok = 'Lampiran_'.$model->id.'.'.$lampiran->extension;
            }
            // $model->status = 'review pic';
            $model->status_pic = 'draft';
            $model->tanggal_submit = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen tersimpan, status masih dalam draft. Silahkan kirim ke pic manager');
                return $this->redirect(['index']);
            }
        }

        return $this->render('tindakan', [
            'model' => $model,
            'user_pic' => $user_pic,
        ]);
    }

    /**
     * Updated an existing Regkomtindakan model.
     * For submission revisi tindakan komitmen from user PIC.
     * If update is successful, the browser will be redirected to 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdaterevisi($id)
    {
        $model = $this->findModelKomitmen($id);
        $jenis = $model->jnsKomitmen->jenis->jenis;

        // dropdown PIC
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();

        // use scenarios
        $model->scenario = Regkomtindakan::SCENARIO_TINDAKAN;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiran = UploadedFile::getInstance($model, 'nama_dok');
            if (!empty($lampiran)) {
                $lampiran->saveAs(Yii::getAlias('@lampiran/komitmen/').'Lampiran_'.$model->id.'.'.$lampiran->extension);
                $model->nama_dok = 'Lampiran_'.$model->id.'.'.$lampiran->extension;
            }

            $model->tanggal_submit = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen tersimpan. Silahkan kirim ke pic manager');
                return $this->redirect(['indexrevisipic']);
            }
        }

        return $this->render('update_revisi', [
            'model' => $model,
            'user_pic' => $user_pic,
        ]);
    }

    /**
     * Updated an existing Regkomtindakan model.
     * For submission tindakan komitmen from pic to manager pic.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionKirim($id)
    {
        $model = $this->findModelKomitmen($id);
        $model->status_pic = 'review manager';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Item telah dikirim ke pic manager, dalam proses review');
            $this->redirect(['index']);
        }
    }

    public function actionKirimrevisi($id)
    {
        $model = $this->findModelKomitmen($id);
        $model->status_pic = 'review manager';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Item telah dikirim ke pic manager, dalam proses review');
            $this->redirect(['indexrevisipic']);
        }
    }

    /**
     * Download lampiran komitmen
     */
    public function actionDownload($id) 
    {
        $model = $this->findModelKomitmen($id);
        $path = Yii::getAlias('@lampiran/komitmen/').$model->nama_dok;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, $model->nama_dok);
        }
    }

    /**
     * Deletes an existing file in Regkomtindakan model and delete on directory file.
     * If deletion is successful, the browser will be redirected to the 'updaterevisi' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletedok($id)
    {
        $model = $this->findModelKomitmen($id);
        $filePath = Yii::getAlias('@lampiran/komitmen/').$model->nama_dok;
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
     * Find the Regkomtindakan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Regkomtindakan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelKomitmen($id)
    {
        if (($model = Regkomtindakan::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Find the Regjnskomitmen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID.
     * @return Regjnkomitmen the load model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findJenisKomitmen($id)
    {
        if (($model = Regjnskomitmen::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

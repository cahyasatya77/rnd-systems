<?php

namespace backend\controllers;

use common\models\Justifikasi;
use backend\models\JustifikasiSearch;
use backend\models\Model;
use backend\models\Modelstatis;
use common\models\Justifikasidetail;
use common\models\Logjustifikasikomitmen;
use common\models\Registrasikomitmen;
use common\models\Regjnskomitmen;
use common\models\Regkomtindakan;
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
 * JustifikasiController implements the CRUD actions for Justifikasi model.
 */
class JustifikasiController extends Controller
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
                            'actions' => [
                                'index', 'create', 'update', 'delete', 'view', 'deadline',
                                'firstcreate', 'komitmen', 'kirim', 'tindakan',
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['indexreview', 'approvernd', 'revisirnd', 'create'],
                            'allow' => (Yii::$app->user->idDept == 'D0001' and Yii::$app->user->idSection == 'D0001SC001'),
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexreviewmanager', 'approverndmanager', 'revisimanager'],
                            'allow' => (Yii::$app->user->idDept == 'D0001' and Yii::$app->user->level == 'manager'),
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['indexapprovepic', 'approvepic', 'revisipic'],
                            'allow' => Yii::$app->user->level == 'manager',
                            'roles' => ['@']
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Get deadline komitmen
     */
    public function actionDeadline($kode)
    {
        $model = Regkomtindakan::findOne($kode);

        return Json::encode($model);
    }

    /**
     * Get data komitmen
     */
    public function actionKomitmen($id)
    {
        $user_dept = User::findOne(Yii::$app->user->id);
        $model = Regjnskomitmen::find()
                    ->joinWith('komitmenTindakan')
                    ->joinWith('komitmenTindakan.user')
                    ->where(['id_komitmen' => $id])
                    ->andWhere(['user.id_dept' => $user_dept->id_dept])
                    ->all();
        
        if (count($model) > 0) {
            foreach ($model as $komitmen) {
                echo "<option value='".$komitmen['id']."'>".$komitmen['jenis']['jenis']."</option>";
            } 
        } else {
            echo "<option>-</option>";
        }
    }

    /**
     * Get data tindakan
     */
    public function actionTindakan($id)
    {
        $user_dept = User::findOne(Yii::$app->user->id);
        $model = Regkomtindakan::find()
                ->joinWith('user')
                ->where(['id_jns_komitmen' => $id])
                ->andWhere(['user.id_dept' => $user_dept->id_dept])
                ->all();
        if (count($model) > 0) {
            foreach ($model as $tindakan) {
                echo "<option value='".$tindakan['id']."'>".$tindakan['dokumen']."</option>";
            } 
        } else {
            echo "<option>-</option>";
        }
    }

    /**
     * Lists all Justifikasi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dept = Yii::$app->user->idDept;

        $searchModel = new JustifikasiSearch();
        $dataProvider = $searchModel->searchDraft($this->request->queryParams, $dept);
        // data provider for index revisi
        $dataRevisi = $searchModel->searchRevisi($this->request->queryParams, $dept);
        // data provider for index waiting approve
        $dataWaiting = $searchModel->searchWaiting($this->request->queryParams, $dept);

        // var_dump($dataWaiting->getTotalCount());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataRevisi' => $dataRevisi,
            'dataWaiting' => $dataWaiting,
        ]);
    }

    public function actionIndexapprovepic()
    {
        $user = Yii::$app->user->id;

        $searchModel = new JustifikasiSearch();
        $dataProvider = $searchModel->searchKirim($this->request->queryParams, $user);

        return $this->render('manager_pic/index', [
            'seaerchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexreview()
    {
        $searchModel = new JustifikasiSearch();
        $dataProvider = $searchModel->searchReview($this->request->queryParams);

        return $this->render('rnd/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexreviewmanager()
    {
        $searchModel = new JustifikasiSearch();
        $dataProvider = $searchModel->searchReviewManager($this->request->queryParams);

        return $this->render('rnd_manager/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Justifikasi model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $komitmen = $this->findProduk($model->id_komitmen);
        $jenis_komitmen = $this->findJenisKomitmen($model->id_jenis_komitmen);

        $query = Justifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'komitmen' => $komitmen,
            'jenis_komitmen' => $jenis_komitmen,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Justifikasi model for choose condition ID komitmen and Komitmen.
     * If creation is successful, the browser will be redirected to the `create` page.
     * @return string|\yii\web\Response
     */
    public function actionFirstcreate()
    {
        $model = new Modelstatis();

        $user_dept = User::findOne(Yii::$app->user->id);

        $produk = Registrasikomitmen::find()
                ->select(['registrasi_komitmen.id','CONCAT(id_transaksi, " / ", nama_obat) as item'])
                ->joinWith('jenisKomitmen')
                ->joinWith('jenisKomitmen.komitmenTindakan')
                ->joinWith('jenisKomitmen.komitmenTindakan.user')
                ->where(['OR', 
                    ['reg_kom_tindakan.status_pic' => 'open'], 
                    ['reg_kom_tindakan.status_pic' => 'revisi']
                ])
                ->andWhere(['user.id' => Yii::$app->user->id])
                ->asArray()
                ->all();
        // var_dump(ArrayHelper::map($produk, 'id', 'item'));
        // die();

        $model->scenario = Modelstatis::SCENARIO_JUSTIFIKASI;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $id = $model->id_komitmen;
                $kom = $model->komitmen;
                $tindakan = $model->tindakan;
                return $this->redirect(['create', 'id' => $id, 'kom' => $kom, 'tnd' => $tindakan]);
            }
        }

        return $this->render('first_create', [
            'model' => $model,
            'produk' => $produk
        ]);
    }

    /**
     * Creates a new Justifikasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id, $kom, $tnd)
    {
        $model_produk = $this->findProduk($id);
        $jenis_komitmen = $this->findJenisKomitmen($kom);
        $tindakan = $this->findTindakanKomitmen($tnd);

        $model = new Justifikasi();
        $model_detail = [new Justifikasidetail];

        // List items select2
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();
        $data_komitmen = Regkomtindakan::find()->where(['id_jns_komitmen' => $jenis_komitmen->id])->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model_detail = Model::createMultiple(Justifikasidetail::className());
                Model::loadMultiple($model_detail, Yii::$app->request->post());

                $valid = $model->validate();
                $valid = Model::validateMultiple($model_detail) && $valid;

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $model->id_komitmen = $model_produk->id;
                        $model->id_jenis_komitmen = $jenis_komitmen->id;
                        $model->status = 1;
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
                        Yii::$app->session->setFlash('error', 'Error your transaction function. Pleace check your function');
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
            'model_produk' => $model_produk,
            'jenis_komitmen' => $jenis_komitmen,
            'data_komitmen' => $data_komitmen,
            'user_pic' => $user_pic,
            'tindakan' => $tindakan,
        ]);
    }

    /**
     * Updates an existing Justifikasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_detail = $model->justifikasiDetails;

        $model_produk = $this->findProduk($model->id_komitmen);
        $jenis_komitmen = $this->findJenisKomitmen($model->id_jenis_komitmen);

        // List items select2
        $dept = Yii::$app->user->idDept;
        $user_pic = User::find()->where(['id_dept' => $dept])->andWhere(['level_access' => 'manager'])->all();
        $data_komitmen = Regkomtindakan::find()->where(['id_jns_komitmen' => $jenis_komitmen->id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $idOld = ArrayHelper::map($model_detail, 'id', 'id');
            $model_detail = Model::createMultiple(Justifikasidetail::className(), $model_detail);
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
                            if (! ($success = $detail->save(false))) {
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
                    Yii::$app->session->setFlash('error', 'Error your transaction function. Pleace check your function');
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_detail' => $model_detail,
            'model_produk' => $model_produk,
            'jenis_komitmen' => $jenis_komitmen,
            'user_pic' => $user_pic,
            'data_komitmen' => $data_komitmen,
        ]);
    }

    /**
     * Send justifikasi to the manager PIC
     */
    public function actionKirim($id)
    {
        $model = $this->findModel($id);
        $model->status = 3;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Justifikasi dikirim ke Manager terkait untuk dilakukan approve. Check the waiting list index !.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Approve manager Pic
     */
    public function actionApprovepic($id)
    {
        $model = $this->findModel($id);

        $query = Justifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 4;
            $model->pic_manager_approve = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi approve, dikirim ke rnd department untuk direview');
                return $this->redirect(['indexapprovepic']);
            }
        }

        return $this->render('manager_pic/approve', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Revisi PIC manager
     */
    public function actionRevisipic($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Justifikasi::SCENARIO_REVISI_PIC;
        
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->pic_manager_approve = null;
            $model->status = 2;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi dikirimkan kembali ke PIC terkait dengan status Revisi');
                return $this->redirect(['indexapprovepic']);
            }
        }

        return $this->render('manager_pic/revisi', [
            'model' => $model,
        ]);
    }

    /**
     * Approve and Review R&D Registrasi
     */
    public function actionApprovernd($id)
    {
        $model = $this->findModel($id);

        $query = Justifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 5;
            $model->rnd_approve = new Expression('NOW()');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Justifikasi approve, dikirim ke R&D Manager untuk di review.');
                return $this->redirect(['indexreview']);
            }
        }

        return $this->render('rnd/approve', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Revisi RnD registrasi staff
     */
    public function actionRevisirnd($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Justifikasi::SCENARIO_REVISI_RND;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 2;
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
     * Approve and Review Justifikasi Manager R&D
     */
    public function actionApproverndmanager($id)
    {
        $model = $this->findModel($id);

        $query = Justifikasidetail::find();
        $query->where(['id_justifikasi' => $model->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->status = 6;
                $model->rnd_manager_approve = new Expression('NOW()');
                $success = $model->save();

                if ($success) {
                    foreach ($model->justifikasiDetails as $detail) {
                        $tindakan_komit = Regkomtindakan::findOne($detail->id_kom_tindakan);
                        $tindakan_komit->dead_line = $detail->deadline_new;
                        if (! ($success = $tindakan_komit->save(false))) {
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
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Revisi Justifikasi R&D Manager to PIC
     */
    public function actionRevisimanager($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Justifikasi::SCENARIO_REVISI_MANAGER;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 2;
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
     * Deletes an existing Justifikasi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $detail = $model->justifikasiDetails;
        $idDetail = [];
        foreach ($detail as $list) {
            $idDetail[] = $list->id;
        }

        // Insert to log justifikasi komitmen
        $model_log = new Logjustifikasikomitmen();
        $model_log->id_komitmen = $model->id_komitmen;
        $model_log->id_jenis_komitmen = $model->id_jenis_komitmen;
        $model_log->id_tindakan = implode(',', $idDetail);
        $model_log->alasan_justif = $model->alasan_justif;
        $model_log->create_justif = $model->created_by;
        $model_log->pic_manager = $model->pic_manager;
        $model_log->pic_manager_approve = $model->pic_manager_approve;
        $model_log->rnd_approve = $model->rnd_approve;
        $model_log->rnd_manager_approve = $model->rnd_manager_approve;
        $model_log->note_revisi = $model->note_revisi;
        $model_log->revisi_rnd = $model->revisi_rnd;
        $model_log->revisi_rnd_manager = $model->revisi_rnd_manager;
        $model_log->justif_delete = new Expression('NOW()');

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
     * Finds the Justifikasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Justifikasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Justifikasi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findProduk($id)
    {
        if (($model = Registrasikomitmen::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findJenisKomitmen($id)
    {
        if (($model = Regjnskomitmen::findOne(['id' => $id])) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findTindakanKomitmen($id)
    {
        if (($model = Regkomtindakan::findOne(['id' => $id])) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

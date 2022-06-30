<?php

namespace backend\controllers;

use backend\models\MeetingregistrasiSearch;
use common\models\Meetingregistrasi;
use common\models\Options;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\AccessController;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Aero implements the submit documents to website new aero and update action for full fillment assigment to the R&D Registrasi from Meetingregistrasi model.
 */
class AeroController extends Controller
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
                        'deletelamp' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'history', 'close', 'view', 'download'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * List Notulen Meeting to submit New Aero
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->searchAero($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Display a single Meetingregistasi model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->searchDokumen($this->request->queryParams, $model->id);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List all history notulen meeting submit to New Aero
     * @return mixed
     */
    public function actionHistory()
    {
        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->searchAeroHistory($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();

        return $this->render('history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
        ]);
    }

    /**
     * Updated an existing Meetingresistrasi model,
     * For closed Notulen meeting and submit to website `New Aero`.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClose($id) 
    {
        $model = $this->findModel($id);

        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->searchDokumen($this->request->queryParams, $model->id);

        $model->scenario = Meetingregistrasi::SCENARIO_CLOSE;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $lampiran = UploadedFile::getInstance($model, 'surat_pengantar');
            if (!empty($lampiran)) {
                $name_lamp = 'sp_aero_'.$model->nama_produk.'_'.$model->id.'.'.$lampiran->extension;
                $lampiran->saveAs(Yii::getAlias('@lampiran/surat_pengantar_aero/').$name_lamp);
                $model->surat_pengantar = $name_lamp;
            }
            $model->tanggal_close = new Expression('NOW()');
            $status = Options::find()->where(['table' => 'meeting_reg'])->andWhere(['deskripsi' => 'close'])->one();
            $model->id_status = $status->id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Notulen meeting telah dilakukan close');
                return $this->redirect(['index']);
            }
        }

        return $this->render('close', [
            'model' => $model,
            'searchMode' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Meetingregistrasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Registrasikomitmen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meetingregistrasi::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
<?php

namespace backend\controllers;

use backend\models\MeetingregistrasiSearch;
use common\models\Meetingdokumen;
use common\models\Meetingkategori;
use common\models\Meetingregistrasi;
use common\models\Options;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * MeetingpicController implements the action view or details for all user.
 */
class MeetingpicController extends Controller
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
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'download', 'viewapprove'],
                            'allow' => true,
                            'roles' => ['@']
                        ]
                    ],
                ],
            ],
        );
    }

    /**
     * List all Meetingregistrasi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeetingregistrasiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // List data dropdown
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

    /**
     * Displays a single Meetingregistrasi model, and relation to Meetingkategori model and Meetingdokumen model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new MeetingregistrasiSearch();
        // data Kategori variasi
        $dataVariasi = $searchModel->searchDokumenVariasi($this->request->queryParams, $model->id);
        // data Evaluasu Reg
        $dataEvaluasi = $searchModel->searchDokumenEvaluasi($this->request->queryParams, $model->id);

        return $this->render('view', [
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
     * Finds the Meetingregistrasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Meetingjustifikasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
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
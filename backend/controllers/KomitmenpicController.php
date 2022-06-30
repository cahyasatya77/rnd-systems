<?php

namespace backend\controllers;

use backend\models\RegistrasikomitmenSearch;
use common\models\Options;
use common\models\Registrasikomitmen;
use common\models\Regkomtindakan;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * KomitmenpicController implements the actions for User PIC.
 */
class KomitmenpicController extends Controller
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
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * List all Registrasikomitmen models.
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
        $status = Options::find()->where(['table' => 'komitmen_reg'])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
            'status' => $status,
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

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionViewapprove($id)
    {
        $model = $this->findModelKomitmen($id);

        return $this->render('view_approve', [
            'model' => $model,
        ]);
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

    protected function findModelKomitmen($id)
    {
        if (($model = Regkomtindakan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
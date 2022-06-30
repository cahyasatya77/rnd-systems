<?php

namespace backend\controllers;

use backend\models\RegistrasikomitmenSearch;
use backend\models\RegkomtindakanSearch;
use common\models\Masterjeniskomitmen;
use common\models\Registrasikomitmen;
use common\models\Regjnskomitmen;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Komitmenbpom implements the History and update action for full fillment assignment to R&D Registrasi form Regjnskomitmen model.
 */
class KomitmenbpomController extends Controller
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
                            'actions' => ['history', 'view', 'update', 'download'],
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ],
                ],
            ],
        );
    }

    /**
     * List all history komitmen submit to BPOM
     * @return mixed
     */
    public function actionHistory()
    {
        $searchModel = new RegistrasikomitmenSearch();
        $dataProvider = $searchModel->searchHistoryBpom($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk WHERE status = 'Diproduksi'";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        $komitmen = Masterjeniskomitmen::find()->all();

        return $this->render('history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
            'komitmen' => $komitmen,
        ]);
    }

    /**
     * Display a single Regjnskomitmen model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchDetail = new RegkomtindakanSearch();
        $dataDetail = $searchDetail->searchDetail($this->request->queryParams, $model->id);

        return $this->render('view', [
            'model' => $model,
            'searchDetail' => $searchDetail,
            'dataDetail' => $dataDetail,
        ]);
    }

    /**
     * Updated an existing Regjnskomitmen model.
     * For submission send data to BPOM from R&D Registration.
     * If update is successful, the browser will be redirected to 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found 
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_komitmen = Registrasikomitmen::findOne($model->id_komitmen);

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
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Komitmen telah dilakukan update');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'searchDetail' => $searchDetail,
            'dataDetail' => $dataDetail,
        ]);
    }

    /**
     * Find the Regjnskomitmen model based on its primary key valus.
     * If the model is not found, a 404 HTTP exception will be thriwn.
     * Download Lampiran surat pengantar ke BPOM.
     * 
     * @param int $id ID
     * @return Regjnskomitmen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $path = Yii::getAlias('@lampiran/surat_pengantar/').$model->surat_pengantar;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, $model->surat_pengantar);
        }
    }

    /**
     * Finds the Regjnskomitmen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Registrasikomitmen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Regjnskomitmen::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
<?php

namespace backend\controllers;

use common\models\Mastervariasi;
use backend\models\MastervariasiSearch;
use backend\models\Model;
use common\models\Masterdokumen;
use common\models\Mastervariasidokumen;
use Exception;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * MastervariasiController implements the CRUD actions for Mastervariasi model.
 */
class MastervariasiController extends Controller
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
            ]
        );
    }

    /**
     * Lists all Mastervariasi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MastervariasiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mastervariasi model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new MastervariasiSearch();
        $dataProvider = $searchModel->searchDokumen($this->request->queryParams, $model->id);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Mastervariasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Mastervariasi();
        $model_detail = [new Mastervariasidokumen];

        /**
         * Model detail
         */
        $dokumen = Masterdokumen::find()
                    ->select(['id', 'CONCAT(kode, " - ", deskripsi) as kode'])
                    ->where(['status' => 'active'])
                    ->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model_detail = Model::createMultiple(Mastervariasidokumen::className());
                Model::loadMultiple($model_detail, Yii::$app->request->post());

                $valid = $model->validate();
                $valid = Model::validateMultiple($model_detail) && $valid;

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $success = $model->save();

                        if ($success) {
                            foreach ($model_detail as $detail) {
                                $detail->id_master_variasi = $model->id;
                                if (!($success = $detail->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        if ($success) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Kategori variasi berhasil ditambahkan.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
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
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Updates an existing Mastervariasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_detail = $model->mastervariasidokumens;

        /**
         * Model detail
         */
        $dokumen = Masterdokumen::find()
                    ->select(['id', 'CONCAT(kode, " - ", deskripsi) as kode'])
                    ->where(['status' => 'active'])
                    ->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $oldDokId = ArrayHelper::map($model_detail, 'id', 'id');
            $model_detail = Model::createMultiple(Mastervariasidokumen::className(), $model_detail);
            Model::loadMultiple($model_detail, Yii::$app->request->post());
            $deleteDetail = array_diff($oldDokId, array_filter(ArrayHelper::map($model_detail, 'id', 'id')));

            // validate
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_detail) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($success = $model->save(false)) {
                        if (!empty($deleteDetail)) {
                            Mastervariasidokumen::deleteAll(['id' => $deleteDetail]);
                        }

                        foreach ($model_detail as $detail) {
                            $detail->id_master_variasi = $model->id;
                            if (!($success = $detail->save(false))) {
                                break;
                            }
                        }
                    }

                    if ($success) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Data master kategori variasi berhasil dilakukan perubahan.');
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
            'model_detail' => (empty($model_detail)) ? [new Mastervariasidokumen] : $model_detail,
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Deletes an existing Mastervariasi model.
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
     * Finds the Mastervariasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Mastervariasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mastervariasi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

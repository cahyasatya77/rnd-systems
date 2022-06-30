<?php

namespace backend\controllers;

use backend\models\UserSearch;
use common\models\User;
use backend\models\SignupForm;
use backend\models\UpdateUser;
use common\models\Department;
use common\models\Section;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * User Controller
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'signup', 'view', 'update'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['profile'],
                        'allow' => Yii::$app->user->id,
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post']
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays a single User model.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Display User Profile.
     * Only user id can access this function where Id extend 'Yii::$app->user->id'.
     * 
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model canot be found
     */
    public function actionProfile($id)
    {
        $model = $this->findModel($id);

        try {
            $new_model = new UpdateUser($id);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($new_model->load(Yii::$app->request->post()) && $new_model->validate() && $new_model->changePassword()) {
            Yii::$app->session->setFlash('success', "Password Changed and Please relogin for checked new password !");
            // $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
            'new_model' => $new_model,
        ]);
    }

    /**
     * Display homepage.
     * 
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Signs user up.
     * 
     * @return mixed
     */
    public function actionSignup()
    {
        $dept = Department::find()->all();
        $section = Section::find()->all();
        $model = new SignupForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'User has been added. Please check index user.');

            return $this->redirect('index');
        } 
        // else {
        //     $model->loadDefaultValues();
        // }

        return $this->render('signup', [
            'model' => $model,
            'dept' => $dept,
            'section' => $section,
        ]);
    }

    /**
     * Updates an existing User model.
     * If updated is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $dept = Department::find()->all();
        $section = Section::find()->all();

        try {
            $model = new UpdateUser($id);
            $model_old = User::findOne($id);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $model->scenario = UpdateUser::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->updateUs();

            Yii::$app->session->setFlash('success', 'User updated please check and re-login.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'model_old' => $model_old,
            'dept' => $dept,
            'section' => $section,
        ]);
    }

    /**
     * Finds the user model based no its primary key value.
     * If the models is nor found. a 404 HTTP exceptions will be throws.
     * @param string $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) != null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
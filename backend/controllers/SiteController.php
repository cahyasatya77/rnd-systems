<?php

namespace backend\controllers;

use common\models\Justifikasi;
use common\models\LoginForm;
use common\models\Meetingdokumen;
use common\models\Meetingjustifikasi;
use common\models\Meetingregistrasi;
use common\models\Registrasikomitmen;
use common\models\Regjnskomitmen;
use common\models\Regkomtindakan;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->id;
        // Komitmen Registrasi
        $app_komit = Registrasikomitmen::find()
                        ->where(['id_status' => 9])
                        ->andWhere('rnd_manager IS NOT NULL')
                        ->andWhere('approve_manager IS NULL')
                        ->all();
        $revisi_komit = Registrasikomitmen::find()
                        ->where(['id_status' => 8])
                        ->all();
        $review_rnd = Regkomtindakan::find()
                        ->where(['status' => 'review rnd'])
                        ->all();
        $review_rnd_manager = Regkomtindakan::find()
                        ->where(['status' => 'review rnd manager'])
                        ->all();
        $app_justifikasi = Justifikasi::find()
                        ->joinWith('options')
                        ->where(['options.deskripsi' => 'kirim'])
                        ->andWhere(['pic_manager' => $user])
                        ->andWhere('pic_manager_approve IS NULL')
                        ->all();

        // Meeting Registrasi
        $app_meeting = Meetingregistrasi::find()
                        ->where(['id_status' => 14])
                        ->andWhere('rnd_manager IS NOT NULL')
                        ->andWhere('approve_manager IS NULL')
                        ->all();
        $revisi_meeting = Meetingregistrasi::find()
                        ->where(['id_status' => 13])
                        ->all();
        $review_meeting_rnd = Meetingdokumen::find()
                        ->where(['status' => 'review rnd'])
                        ->all();
        $review_meeting_rnd_manager = Meetingdokumen::find()
                        ->where(['status' => 'review rnd manager'])
                        ->all();
        $app_justif_meeting = Meetingjustifikasi::find()
                        ->joinWith('status')
                        ->where(['options.deskripsi' => 'kirim'])
                        ->andWhere(['pic_manager' => $user])
                        ->andWhere('pic_manager_approve IS NULL')
                        ->all();

        return $this->render('index', [
            // Komitmen Registrasi
            'app_komit' => $app_komit,
            'revisi_komit' => $revisi_komit,
            'review_rnd' => $review_rnd,
            'review_rnd_manager' => $review_rnd_manager,
            'app_justifikasi' => $app_justifikasi,
            // Meeting Registrasi
            'app_meeting' => $app_meeting,
            'revisi_meeting' => $revisi_meeting,
            'review_meeting_rnd' => $review_meeting_rnd,
            'review_meeting_rnd_manager' => $review_meeting_rnd_manager,
            'app_justif_meeting' => $app_justif_meeting,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        // if (isset($_POST['LoginForm'])) {
        //     return $this->goBack();
        // }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

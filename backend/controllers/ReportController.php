<?php

namespace backend\controllers;

use backend\models\ReportSearch;
use common\models\Masterjeniskomitmen;
use common\models\Masterjenismeetingreg;
use common\models\Meetingdokumen;
use common\models\Regkomtindakan;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ReportController implements the view report action for all models.
 */
class ReportController extends Controller
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
                            'actions' => [
                                'komitmen', 'nie', 'ednie', 'statusreg', 'deadline-old',
                                'meeting', 'deadline-old-meeting', 'bentuk-sediaan'
                            ],
                            'allow' => true,
                            'roles' => ['@']
                        ]
                    ],
                ],
            ],
        );
    }

    /**
     * Get number NIE
     */
    public function actionNie($id)
    {
        $sql = "SELECT n.* FROM tbl_nie n JOIN tbl_produk p ON n.kd_produk = p.kd_produk WHERE p.id = '".$id."'";
        $nie = Yii::$app->db_pm->createCommand($sql)->queryOne();
        if ($nie['nomor_nie'] == null) {
            return '-';
        } else {
            return $nie['nomor_nie'];
        }
    }

    /**
     * Get expired date NIE
     */
    public function actionEdnie($id)
    {
        $sql = "SELECT n.ed FROM tbl_nie n JOIN tbl_produk p ON n.kd_produk = p.kd_produk WHERE p.id = '".$id."'";
        $nie = Yii::$app->db_pm->createCommand($sql)->queryOne();
        if ($nie['ed'] == null) {
            return '-';
        } else {
            return $nie['ed'];
        }
    }

    /**
     * Get number NIE
     */
    public function actionBentukSediaan($id)
    {
        $sql = "SELECT bentuk_sediaan FROM tbl_produk WHERE id = '".$id."'";
        $nie = Yii::$app->db_pm->createCommand($sql)->queryOne();
        if ($nie['bentuk_sediaan'] == null) {
            return '-';
        } else {
            return $nie['bentuk_sediaan'];
        }
    }

    /**
     * Status Registrasi
     */
    public function actionStatusreg($id)
    {
        $sql = "SELECT reg.status FROM tbl_registrasi reg 
        JOIN tbl_status_registrasi streg ON streg.kd_status = reg.kd_status
        JOIN tbl_produk prod ON prod.kd_produk = streg.kd_produk 
        WHERE prod.id = '".$id."'";
        $reg = Yii::$app->db_pm->createCommand($sql)->queryOne();

        if ($reg['status'] == '2') {
            return 'Disetujui';
        } elseif ($reg['status'] == '1') {
            return 'Evaluasi';
        } elseif ($reg['status'] == '0') {
            return 'Dalam Proses';
        } else {
            return '-';
        }
    }

    /**
     * Deadline old, get from Justifikasidetail model.
     * @param $id ID Regkomtindakan model
     * @return string
     */
    public function actionDeadlineOld($id)
    {
        $model = Regkomtindakan::findOne($id);
        $model_justif = $model->justifikasiDetails;
        if (!empty($model_justif)) {
            $deadline = [];
            foreach ($model_justif as $justif) {
                $date = date('d M Y', strtotime($justif->deadline_old));
                $deadline[] = '<s>'.$date.'</s>';
            }
            return implode('<br>', $deadline);
        } else {
            return '-';
        }
    }

    public function actionDeadlineOldMeeting($id)
    {
        $model = Meetingdokumen::findOne($id);
        $model_justif = $model->justifikasiDetails;
        if (!empty($model_justif)) {
            $deadline = [];
            foreach ($model_justif as $justif) {
                $date = date('d M Y', strtotime($justif->deadline_old));
                $deadline[] = '<s>'.$date.'</s>';
            }
            return implode('<br>', $deadline);
        } else {
            return '-';
        }
    }

    /**
     * List all Resistrasikomitmen models.
     * 
     * @return string
     */
    public function actionKomitmen()
    {
        $searchModel = new ReportSearch();
        $dataProvider  = $searchModel->searchKomitmen($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        // $admin = [1,2];
        $user = User::find()->where(['not in', 'id', [1,2]])->all();
        $jenis_komitmen = Masterjeniskomitmen::find()->all();

        return $this->render('komitmen', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
            'user' => $user,
            'jenis_komitmen' => $jenis_komitmen,
        ]);
    }

    /**
     * List all Meetingregistrasi models.
     * 
     * @return string
     */
    public function actionMeeting()
    {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->searchMeeting($this->request->queryParams);

        // List data dropdown
        $connection = Yii::$app->db_pm;
        $sql_produk = "SELECT id, nama_produk FROM tbl_produk";
        $produk = $connection->createCommand($sql_produk)->queryAll();
        // $admin = [1,2];
        $user = User::find()->where(['not in', 'id', [1,2]])->all();
        $jenis_kategori = Masterjenismeetingreg::find()->all();

        return $this->render('meeting', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'produk' => $produk,
            'user' => $user,
            'jenis_kategori' => $jenis_kategori,
        ]);
    }
}
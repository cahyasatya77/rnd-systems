<?php

use common\models\Meetingdokumen;
use common\models\Regkomtindakan;
use yii\helpers\Html;
use yii\helpers\Url;

// User Login
$user = Yii::$app->user->id;
// Komitmen Registrasi
$tagihan_komit = Regkomtindakan::find()
                ->where(['status' => 'open'])
                ->andWhere(['or',['status_pic' => 'open'], ['status_pic' => 'draft']])
                ->andWhere(['pic' => $user])
                ->count();
$revisi_tagihan = Regkomtindakan::find()
                ->where(['status_pic' => 'revisi'])
                ->andWhere(['pic' => $user])
                ->count();
$app_tagihan = Regkomtindakan::find()
                ->where(['status_pic' => 'review manager'])
                ->andWhere(['manager_pic' => $user])
                ->count();
// Meeting Registrasi
$tagihan_meeting = Meetingdokumen::find()
                ->where(['status' => 'open'])
                ->andWhere(['or', ['status_pic' => 'open'], ['status_pic' => 'draft']])
                ->andWhere(['pic' => $user])
                ->count();
$revisi_tagihan_meeting = Meetingdokumen::find()
                ->where(['status_pic' => 'revisi'])
                ->andWhere(['pic' => $user])
                ->count();
$app_tagihan_meeting = Meetingdokumen::find()
                ->where(['status_pic' => 'review manager'])
                ->andWhere(['pic_manager' => $user])
                ->count();
// All notif
if (Yii::$app->user->level == 'manager') {
    $all_notif = $tagihan_komit + $revisi_tagihan + $app_tagihan + $tagihan_meeting + $app_tagihan_meeting + $revisi_tagihan_meeting;
} else {
    $all_notif = $tagihan_komit + $revisi_tagihan + $revisi_tagihan_meeting + $tagihan_meeting;
}
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-teal navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <?php if ($all_notif != "0") :?>
                    <span class="badge badge-warning navbar-badge"><?= $all_notif; ?></span>
                <?php endif?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Komitmen Registrasi</span>
                <div class="dropdown-divider"></div>
                <a href=<?= Url::to(['tagihan/index'])?> class="dropdown-item">
                    <i class="fas fa-file-invoice mr-2"></i> Tagihan Komitmen
                    <span class="float-right text-light text-sm badge badge-primary"><?= $tagihan_komit; ?></span>
                </a>
                <div class="dropdown-divider"></div>
                <a href=<?= Url::to(['tagihan/indexrevisipic'])?> class="dropdown-item">
                    <i class="fas fa-history mr-2"></i> Revisi Komitmen
                    <span class="float-right text-light text-sm badge badge-danger"><?= $revisi_tagihan; ?></span>
                </a>
                <?php if (Yii::$app->user->level == 'manager'):?>
                    <div class="dropdown-divider"></div>
                    <a href=<?= Url::to(['tagihan/indexapprove'])?> class="dropdown-item">
                        <i class="fas fa-check mr-2"></i> Approve Tagihan
                        <span class="float-right text-light text-sm badge badge-success"><?= $app_tagihan; ?></span>
                    </a>
                <?php endif;?>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item dropdown-header">Meeting Registrasi</span>
                <div class="dropdown-divider"></div>
                <a href=<?= Url::to(['tagihanmeeting/index'])?> class="dropdown-item">
                    <i class="fas fa-file-invoice mr-2"></i> Tagihan Meeting 
                    <span class="float-right text-light text-sm badge badge-primary"><?= $tagihan_meeting; ?></span>
                </a>
                <div class="dropdown-divider"></div>
                <a href=<?= Url::to(['tagihanmeeting/indexrevisipic'])?> class="dropdown-item">
                    <i class="fas fa-history mr-2"></i> Revisi Tagihan Meeting
                    <span class="float-right text-light text-sm badge badge-danger"><?= $revisi_tagihan_meeting; ?></span>
                </a>
                <?php if (Yii::$app->user->level == 'manager'):?>
                    <div class="dropdown-divider"></div>
                    <a href=<?= Url::to(['tagihanmeeting/indexapprove'])?> class="dropdown-item">
                        <i class="fas fa-check mr-2"></i> Approve Tagihan Meeting
                        <span class="float-right text-light text-sm badge badge-success"><?= $app_tagihan_meeting; ?></span>
                    </a>
                <?php endif;?>
                <!-- <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
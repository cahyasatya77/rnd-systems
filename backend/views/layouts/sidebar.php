<?php

use common\models\Justifikasi;
use common\models\Meetingjustifikasi;
use common\models\Meetingregistrasi;
use common\models\Regjnskomitmen;
use yii\widgets\Menu;
use yii\helpers\Html;

$dept = Yii::$app->user->idDept;

// Registrasi Komitmen
$rev_justif = Justifikasi::find()
    ->joinWith('options')
    ->joinWith('user')
    ->where(['options.deskripsi' => 'revisi'])
    ->andWhere(['user.id_dept' => $dept])
    ->count();
$review_justif = Justifikasi::find()
    ->joinWith('options')
    ->where(['options.deskripsi' => 'review rnd'])
    ->andWhere('rnd_approve IS NULL')
    ->count();
$review_justif_manager = Justifikasi::find()
    ->joinWith('options')
    ->where(['options.deskripsi' => 'review rnd manager'])
    ->andWhere(['AND','rnd_manager_approve IS NULL', 'rnd_approve IS NOT NULL'])
    ->count();
// Meeting Registrasi
$rev_justif_meeting = Meetingjustifikasi::find()
    ->joinWith('status')
    ->joinWith('user')
    ->where(['options.deskripsi' => 'revisi'])
    ->andWhere(['user.id_dept' => $dept])
    ->count();
$review_justif_meeting = Meetingjustifikasi::find()
    ->joinWith('status')
    ->where(['options.deskripsi' => 'review rnd'])
    ->andWhere('rnd_approve IS NULL')
    ->count();
$review_justif_meeting_manager = Meetingjustifikasi::find()
    ->joinWith('status')
    ->where(['options.deskripsi' => 'review rnd manager'])
    ->andWhere(['AND', 'rnd_manager_approve IS NULL', 'rnd_approve IS NOT NULL'])
    ->count();
?>
<aside class="main-sidebar sidebar-dark-teal elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Yii::$app->request->baseUrl;?>" class="brand-link">
        <img src="<?= Yii::$app->request->baseUrl.'/img/favicon_p.png'?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight">R&D Systems</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= Yii::$app->request->baseUrl.'/img/avatar.svg'?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <?= Html::a(Yii::$app->user->username, ['user/profile', 'id' => Yii::$app->user->id], ['class' => 'd-block'])?>
                <!-- <a href="" class="d-block">< ?= Yii::$app->user->username;?></a> -->
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <?php
            $user_management = [
                'label' => 'User Management',
                'icon' => 'users',
                'items' => [
                    ['label' => 'Users', 'url' => ['user/index'], 'iconStyle' => 'far'],
                    ['label' => 'Department', 'url' => ['department/index'], 'iconStyle' => 'far'],
                    ['label' => 'Section Department', 'url' => ['section/index'], 'iconStyle' => 'far'],
                ],
            ];
            // $komitmen_registasi = [
            //     ['labbel' => 'Komitmen Registasi', 'header' => true],
            //     ['label' => 'Komitmen', 'url' => ['registrasikomitmen/index'], 'iconStyle' => 'bookmark'],
            //     ['label' => 'Jenis Komitmen', 'url' => ['masterjeniskomitmen/index'], 'iconStyle' => 'far'],
            // ];
            $komitmen_registasi = [
                'label' => 'Komitmen Registrasi', 
                'icon' => 'bookmark',
                'items' => [
                    ['label' => 'Komitmen', 'url' => ['registrasikomitmen/index'], 'iconStyle' => 'far'],
                    ['label' => 'Jenis Komitmen', 'url' => ['masterjeniskomitmen/index'], 'iconStyle' => 'far'],
                ],
            ];
            $master_meeting = [
                'label' => 'Master Meeting',
                'icon' => 'bars',
                // 'iconStyle' => 'far',
                'items' => [
                    ['label' => 'Kategori Variasi', 'url' => ['mastervariasi/index'], 'iconStyle' => 'far'],
                    ['label' => 'Dokumen', 'url' => ['masterdokumen/index'], 'iconStyle' => 'far'],
                ],
            ];
            $meeting_registrasi = [
                'label' => 'Meeting Registrasi',
                'icon' => 'handshake',
                'iconStyle' => 'far',
                'items' => [
                    ['label' => 'Notulen Meeting', 'url' => ['meetingregistrasi/index'], 'iconStyle' => 'far'],
                    [
                        'label' => 'NEW AERO', 
                        'badge' => '<span class="right badge badge-primary">0</span>',
                        'url' => ['meetingregistrasi/indexbpom'], 
                        'iconStyle' => 'far'
                    ],
                    ['label' => 'Master Jenis Meeting', 'url' => ['masterjenismeetingreg/index'], 'iconStyle' => 'far'],
                    $master_meeting,
                ],
            ];
            
            $report_komitmen = [
                'label' => 'Report Komitmen','url' => ['report/komitmen'], 'iconStyle' => 'far', 'icon' => 'file-pdf',
            ];
            $report_meeting = [
                'label' => 'Report Meeting', 'url' => ['report/meeting'], 'iconStyle' => 'far', 'icon' => 'file-pdf',
            ];
        ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php if (Yii::$app->user->idDept == 'D0002') :?>
                <?= \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'User Menu', 'header' => true],
                        $komitmen_registasi,
                        $meeting_registrasi,
                        $user_management,
                        ['label' => 'Administrator', 'header' => true],
                        ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                        ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                        ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                    ],
                ]);
                ?>
            <?php elseif (Yii::$app->user->idDept == 'D0001') :?>
                <?php if (Yii::$app->user->idSection == 'D0001SC001') : ?>
                    <?php
                        $bpom = Regjnskomitmen::find()->where(['status' => 'approve'])->count();   
                        // $aero = Meetingregistrasi::find()
                        //             ->joinWith('meetingkategori')
                        //             ->where(['meeting_kategori.id_jenis_meeting' => 1])
                        //             ->andWhere(['meeting_kategori.status' => 'approve'])
                        //             ->andWhere(['NOT IN', 'id_status', '16'])
                        //             ->groupBy('meeting_registrasi.id')
                        //             ->count();
                        $aero = Meetingregistrasi::find()
                                ->where(['id_status' => 17])
                                ->count();
                        // var_dump($aero);
                        // die();
                    ?>
                    <?= \hail812\adminlte\widgets\Menu::widget([
                        'items' => [
                            ['label' => 'Komitmen Registasi', 'header' => true],
                            ['label' => 'Komitmen', 'url' => ['registrasikomitmen/index'], 'icon' => 'bookmark'],
                            ['label' => 'Master Jenis Komitmen', 'url' => ['masterjeniskomitmen/index'], 'icon' => 'bars'],
                            [
                                'label' => 'BPOM', 
                                'badge' => '<span class="right badge badge-primary">'.$bpom.'</span>',
                                'url' => ['registrasikomitmen/indexbpom'], 
                                'icon' => 'hospital'
                            ],
                            [
                                'label' => 'Justifikasi Komitmen', 
                                'badge' => '<span class="right badge badge-warning">'.$rev_justif.'</span>',
                                'url' => ['justifikasi/index'], 
                                'icon' => 'balance-scale-right'
                            ],
                            [
                                'label' => 'Review Just Komitmen', 
                                'badge' => '<span class="right badge badge-success">'.$review_justif.'</span>',
                                'url' => ['justifikasi/indexreview'], 
                                // 'iconStyle' => 'far',
                                'icon' => 'info'
                            ],
                            $report_komitmen,

                            // Meeting Registrasi
                            ['label' => 'Meeting Registasi', 'header' => true],
                            ['label' => 'Notulen Meeting', 'url' => ['meetingregistrasi/index'], 'icon' => 'bookmark'],
                            [
                                'label' => 'NEW AERO', 
                                'badge' => '<span class="right badge badge-primary">'.$aero.'</span>',
                                'url' => ['aero/index'], 
                                'icon' => 'hospital'
                            ],
                            $master_meeting,
                            [
                                'label' => 'Justifikasi Meeting', 
                                'badge' => '<span class="right badge badge-warning">'.$rev_justif_meeting.'</span>',
                                'url' => ['meetingjustifikasi/index'], 
                                'icon' => 'balance-scale-right'
                            ],
                            [
                                'label' => 'Review Just Meeting', 
                                'badge' => '<span class="right badge badge-success">'.$review_justif_meeting.'</span>',
                                'url' => ['meetingjustifikasi/indexreview'], 
                                // 'iconStyle' => 'far',
                                'icon' => 'info'
                            ],
                            $report_meeting
                        ],
                    ]);
                    ?>
                <?php elseif (Yii::$app->user->level == 'manager') :?>
                    <?= \hail812\adminlte\widgets\Menu::widget([
                        'items' => [
                            ['label' => 'Komitmen Menu', 'header' => true],
                            ['label' => 'Komitmen Registrasi', 'url' => ['komitmenpic/index'], 'icon' => 'bookmark'],
                            [
                                'label' => 'Review Justifikasi', 
                                'badge' => '<span class="right badge badge-warning">'.$review_justif_manager.'</span>',
                                'url' => ['justifikasi/indexreviewmanager'], 
                                // 'iconStyle' => 'far',
                                'icon' => 'info'
                            ],
                            $report_komitmen,
                            // Meeting Registrasi
                            ['label' => 'Meeting Registasi', 'header' => true],
                            ['label' => 'Notulen Meeting', 'url' => ['meetingpic/index'], 'icon' => 'bookmark'],
                            [
                                'label' => 'Review Justifikasi Meeting', 
                                'badge' => '<span class="right badge badge-warning">'.$review_justif_meeting_manager.'</span>',
                                'url' => ['meetingjustifikasi/indexreviewmanager'], 
                                // 'iconStyle' => 'far',
                                'icon' => 'info'
                            ],
                            $report_meeting
                        ],
                    ]);
                    ?>
                <?php else :?>
                    <?= \hail812\adminlte\widgets\Menu::widget([
                        'items' => [
                            ['label' => 'Komitmen Menu', 'header' => true],
                            ['label' => 'Komitmen Registrasi', 'url' => ['komitmenpic/index'], 'icon' => 'bookmark'],
                            [
                                'label' => 'Justifikasi', 
                                'badge' => '<span class="right badge badge-warning">'.$rev_justif.'</span>',
                                'url' => ['justifikasi/index'], 
                                'icon' => 'balance-scale-right'
                            ],
                            $report_komitmen,
                            // Meeting Registrasi
                            ['label' => 'Meeting Registasi', 'header' => true],
                            ['label' => 'Notulen Meeting', 'url' => ['meetingpic/index'], 'icon' => 'bookmark'],
                            [
                                'label' => 'Justifikasi Meeting', 
                                'badge' => '<span class="right badge badge-warning">'.$rev_justif_meeting.'</span>',
                                'url' => ['meetingjustifikasi/index'], 
                                'icon' => 'balance-scale-right'
                            ],
                            $report_meeting,
                        ],
                    ]);
                    ?>
                <?php endif;?>
            <?php else :?>
                <?= \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'Komitmen Menu', 'header' => true],
                        ['label' => 'Komitmen Registrasi', 'url' => ['komitmenpic/index'], 'icon' => 'bookmark'],
                        [
                            'label' => 'Justifikasi', 
                            'badge' => '<span class="right badge badge-warning">'.$rev_justif.'</span>',
                            'url' => ['justifikasi/index'], 
                            'icon' => 'balance-scale-right'
                        ],
                        $report_komitmen,
                        // Meeting Registrasi
                        ['label' => 'Meeting Registasi', 'header' => true],
                        ['label' => 'Notulen Meeting', 'url' => ['meetingpic/index'], 'icon' => 'bookmark'],
                        [
                            'label' => 'Justifikasi Meeting', 
                            'badge' => '<span class="right badge badge-warning">'.$rev_justif_meeting.'</span>',
                            'url' => ['meetingjustifikasi/index'], 
                            'icon' => 'balance-scale-right'
                        ],
                        $report_meeting,
                    ],
                ]);
                ?>
            <?php endif;?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
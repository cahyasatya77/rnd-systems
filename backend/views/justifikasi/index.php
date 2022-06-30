<?php

use common\models\Justifikasi;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\JustifikasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$totalDraft = $dataProvider->getTotalCount();
$totalRevisi = $dataRevisi->getTotalCount();
$totalWaiting = $dataWaiting->getTotalCount();

$this->title = 'Justifikasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="justifikasi-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary <?= $totalDraft == 0 ? 'collapsed-card' : ''?> ">
                <div class="card-header">
                    <div class="card-title">Justifikasi Draft</div>
                    
                    <div class="card-tools">
                        <?= Html::a('Create', ['firstcreate'], ['class' => 'btn btn-success']) ?>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <?php if ($totalDraft == 0) :?>
                                <i class="fas fa-plus"></i>
                            <?php else :?>
                                <i class="fas fa-minus"></i>
                            <?php endif;?>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-striped'
                        ],
                        'layout' => '{items}{pager}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'header' => 'Id Transaksi',
                                'value' => function ($model) {
                                    return $model->registrasikomitmen->id_transaksi;
                                },
                            ],
                            [
                                'header' => 'Nama Obat',
                                'value' => function ($model) {
                                    return $model->registrasikomitmen->nama_obat;
                                }
                            ],
                            [
                                'header' => 'Komitmen',
                                'value' => function ($model) {
                                    return $model->jenisKomitmen->jenis->jenis;
                                }
                            ],
                            'alasan_justif:ntext',
                            [
                                'header' => 'Status',
                                'value' => function ($model) {
                                    return $model->options->deskripsi;
                                }
                            ],

                            [
                                'header' => 'Kirim',
                                'class' => ActionColumn::className(),
                                'template' => '{kirim}',
                                'visibleButtons' => [
                                    'kirim' => function ($model) {
                                        if (($model->status == 1) || ($model->status == 2)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                ],
                                'buttons' => [
                                    'kirim' => function ($url, $model){
                                        return Html::a('kirim', $url, [
                                            'class' => 'btn btn-success btn-xs',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to send the justifikasi in your manager ?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                ],
                                'urlCreator' => function ($action, $model, $key) {
                                    if ($action == 'kirim') {
                                        return Url::to(['kirim', 'id' => $model->id]);
                                    }
                                }
                            ],
                            [
                                'header' => 'Action',
                                'class' => ActionColumn::className(),
                                'template' => '{view} {update} {delete}',
                                'visibleButtons' => [
                                    'update' => function ($model) {
                                        if (($model->status == 1) || ($model->status == 2)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    },
                                    'delete' => function ($model) {
                                        if (($model->status == 1) || ($model->status == 2)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    },
                                ],
                                'urlCreator' => function ($action, Justifikasi $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-warning <?= $totalRevisi == 0 ? 'collapsed-card' : '' ?> ">
                <div class="card-header">
                    <div class="card-title">Justifikasi Revisi</div>
                    <!-- tools -->
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <?php if ($totalRevisi == 0) :?>
                                <i class="fas fa-plus"></i>
                            <?php else :?>
                                <i class="fas fa-minus"></i>
                            <?php endif;?>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataRevisi,
                        'tableOptions' => [
                            'class' => 'table table-striped',
                        ],
                        'layout' => '{items}{pager}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'header' => 'Id Transaksi',
                                'value' => function ($model) {
                                    return $model->registrasikomitmen->id_transaksi;
                                },
                            ],
                            [
                                'header' => 'Nama Obat',
                                'value' => function ($model) {
                                    return $model->registrasikomitmen->nama_obat;
                                }
                            ],
                            [
                                'header' => 'Komitmen',
                                'value' => function ($model) {
                                    return $model->jenisKomitmen->jenis->jenis;
                                }
                            ],
                            'alasan_justif:ntext',
                            [
                                'header' => 'Status',
                                'value' => function ($model) {
                                    return $model->options->deskripsi;
                                }
                            ],

                            [
                                'header' => 'Revisi',
                                'class' => ActionColumn::className(),
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url, $model){
                                        return Html::a('revisi', $url, ['class' => 'btn btn-warning btn-xs']);
                                    }
                                ],
                            ],
                            [
                                'header' => 'Kirim',
                                'class' => ActionColumn::className(),
                                'template' => '{kirim}',
                                'visibleButtons' => [
                                    'kirim' => function ($model) {
                                        if (($model->status == 1) || ($model->status == 2)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                ],
                                'buttons' => [
                                    'kirim' => function ($url, $model){
                                        return Html::a('kirim', $url, [
                                            'class' => 'btn btn-success btn-xs',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to send the justifikasi in your manager ?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                ],
                                'urlCreator' => function ($action, $model, $key) {
                                    if ($action == 'kirim') {
                                        return Url::to(['kirim', 'id' => $model->id]);
                                    }
                                }
                            ],
                            [
                                'header' => 'Action',
                                'class' => ActionColumn::className(),
                                'template' => '{view} {delete}',
                                'visibleButtons' => [
                                    'delete' => function ($model) {
                                        if (($model->status == 1) || ($model->status == 2)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    },
                                ],
                                'urlCreator' => function ($action, Justifikasi $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]);?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success <?= $totalWaiting == 0 ? 'collapsed-card' : '' ?>">
                <div class="card-header">
                    <div class="card-title">Justifikasi Waiting to Approve</div>
                    <!-- tools -->
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <?php if ($totalWaiting == 0) :?>
                                <i class="fas fa-plus"></i>
                            <?php else :?>
                                <i class="fas fa-minus"></i>
                            <?php endif;?>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataWaiting,
                        'tableOptions' => [
                            'class' => 'table table-striped',
                        ],
                        'layout' => '{items}{pager}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'header' => 'Id Transaksi',
                                'value' => function ($model) {
                                    return $model->registrasikomitmen->id_transaksi;
                                },
                            ],
                            [
                                'header' => 'Nama Obat',
                                'value' => function ($model) {
                                    return $model->registrasikomitmen->nama_obat;
                                }
                            ],
                            [
                                'header' => 'Komitmen',
                                'value' => function ($model) {
                                    return $model->jenisKomitmen->jenis->jenis;
                                }
                            ],
                            'alasan_justif:ntext',
                            [
                                'header' => 'Status',
                                'value' => function ($model) {
                                    if ($model->options->deskripsi == 'kirim') {
                                        $pic_manager = User::findOne($model->pic_manager);
                                        return 'Menunggu Approve '.$pic_manager->username;
                                    } else {
                                        return $model->options->deskripsi;
                                    }
                                }
                            ],

                            [
                                'header' => 'View',
                                'class' => ActionColumn::className(),
                                'template' => '{view}',
                                'urlCreator' => function ($action, Justifikasi $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]);?>
                </div>
            </div>
        </div>
    </div>

</div>

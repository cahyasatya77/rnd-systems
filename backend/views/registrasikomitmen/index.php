<?php

use common\models\Options;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistrasikomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Komitmen Registrasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasikomitmen-index">
    <!-- Search GridView -->
    <?= $this->render('_search', [
        'model' => $searchModel,
        'produk' => $produk,
    ]); ?>
    <!-- GridView -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-title">Komitmen Registrasi</div>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'layout' => '{items}{pager}',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id_transaksi',
                    'nama_obat',
                    'bentuk_sediaan',
                    'tanggal_pembuatan',
                    [
                        'label' => 'Status',
                        'attribute' => 'status.deskripsi',
                    ],

                    [
                        'header' => 'View',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                            },
                        ],
                    ],
                    [
                        'header' => 'Update',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('update', $url, ['class' => 'btn btn-warning btn-xs']);
                            },
                        ],
                        'visibleButtons' => [
                            'update' => function ($model) {
                                if (($model->id_status == 7) || ($model->id_status == 8)) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        ],
                    ],
                    [
                        'header' => 'Delete',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model) {
                                return Html::a('delete', $url, [
                                    'class' => 'btn btn-danger btn-xs',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item ?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'visibleButtons' => [
                            'delete' => function ($model) {
                                if (($model->id_status == 7) || ($model->id_status == 8)) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        ],
                    ],
                    [
                        'header' => 'Kirim',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{send}',
                        'buttons' => [
                            'send' => function ($url, $model) {
                                $url = Url::to(['kirim', 'id' => $model->id]);
                                return Html::a('kirim', $url, [
                                    'class' => 'btn btn-success btn-xs',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to send this item to R&D Manager ?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'visibleButtons' => [
                            'send' => function ($model) {
                                if (($model->id_status == 7) || ($model->id_status == 8)) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

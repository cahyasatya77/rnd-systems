<?php

use common\models\Meetingregistrasi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingregistrasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Meeting Registrasi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title"> Search</div>
            </div>
            <?= $this->render('_search', [
                'model' => $searchModel,
                'produk' => $produk,
                'status' => $status,
            ]); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
                <?= Html::a('Create', ['create'], ['class' => 'btn btn-success float-right']) ?>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'layout' => '{items}{pager}',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id_transaksi',
                        'nama_produk',
                        'submit_aero',
                        [
                            'header' => 'Status',
                            'value' => 'status.deskripsi',
                        ],

                        // Actions
                        [
                            'header' => 'View',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                                }
                            ],
                        ],
                        [
                            'header' => 'Update',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('update', $url, ['class' => 'btn btn-warning btn-xs']);
                                }
                            ],
                            'visibleButtons' => [
                                'update' => function ($model) {
                                    if (($model->id_status == 12) || ($model->id_status == 13)) {
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
                                }
                            ],
                            'visibleButtons' => [
                                'delete' => function ($model) {
                                    if (($model->id_status == 12) || ($model->id_status == 13)) {
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
                            'template' => '{kirim}',
                            'buttons' => [
                                'kirim' => function ($url, $model) {
                                    $url = Url::to(['kirim', 'id' => $model->id]);
                                    return Html::a('kirim', $url, [
                                        'class' => 'btn btn-success btn-xs',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to send this item to R&D Manager ?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                }
                            ],
                            'visibleButtons' => [
                                'kirim' => function ($model) {
                                    if (($model->id_status == 12) || ($model->id_status == 13)) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            ],
                        ],

                        // [
                        //     'class' => ActionColumn::className(),
                        //     'urlCreator' => function ($action, Meetingregistrasi $model, $key, $index, $column) {
                        //         return Url::toRoute([$action, 'id' => $model->id]);
                        //     }
                        // ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

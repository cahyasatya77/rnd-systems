<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistrasikomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisi Komitmen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasikomitmen-index-revisi">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">Revisi Komitmen Registrasi</div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'layout' => '{items}{pager}',
                'columns' => [
                    [
                        'header' => 'No.',
                        'class' => 'yii\grid\SerialColumn',
                    ],

                    'nama_obat',
                    [
                        'label' => 'Status',
                        'attribute' => 'status.deskripsi',
                    ],
                    [
                        'attribute' => 'note_revisi',
                        'format' => 'raw'
                    ],

                    [
                        'header' => 'View',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $url = Url::to(['viewrevisi', 'id' => $model->id]);
                                return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                            },
                        ],
                    ],
                    [
                        'header' => 'Revisi',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                $url = Url::to(['updaterevisi', 'id' => $model->id]);
                                return Html::a('revisi', $url, ['class' => 'btn btn-warning btn-xs']);
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
            ]);?>
        </div>
    </div>
</div>
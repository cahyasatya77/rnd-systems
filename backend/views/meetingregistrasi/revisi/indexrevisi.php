<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Meetingregistrasi */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Revisi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-title">Revisi</div>
    </div>
    <div class="card-body p-0">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'layout' => '{items}{pager}',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'nama_produk',
                [
                    'header' => 'ED NIE',
                    'value' => function ($data) {
                        return date('d F Y', strtotime($data->ed_nie));
                    }
                ],
                [
                    'header' => 'Submit Aero',
                    'value' => function ($data) {
                        return date('d F Y', strtotime($data->submit_aero));
                    }
                ],
                [
                    'header' => 'Catatan Revisi',
                    'attribute' => 'note_revisi',
                ],
                [
                    'header' => 'Status',
                    'attribute' => 'status.deskripsi',
                ],

                [
                    'header' => 'View',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $url = Url::to(['viewrevisi', 'id' => $model->id]);
                            return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                        }
                    ],
                ],
                [
                    'header' => 'Revisi',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{revisi}',
                    'buttons' => [
                        'revisi' => function ($url, $model) {
                            $url = Url::to(['updaterevisi', 'id' => $model->id]);
                            return Html::a('revisi', $url, ['class' => 'btn btn-warning btn-xs']);
                        }
                    ],
                ],
                [
                    'header' => 'Kirim',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{kirim}',
                    'buttons' => [
                        'kirim' => function ($url, $model) {
                            $url = Url::to(['kirimrevisi', 'id' => $model->id]);
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
            ],
        ])?>
    </div>
</div>
<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TagihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Index Revisi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="regkomtindakan-index-revisi">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Revisi tindakan komitmen</div>
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

                    [
                        'header' => 'Nama Obat',
                        'value' => function ($data) {
                            return $data->jnsKomitmen->komitmen->nama_obat;
                        },
                    ],
                    [
                        'header' => 'Komitmen',
                        'value' => function ($data) {
                            return $data->jnsKomitmen->jenis->jenis;
                        }
                    ],
                    'dokumen',
                    'dead_line',
                    'status_pic',

                    [
                        'header' => 'Revisi',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
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
                                        'confirm' => 'Are you sure want to send this item to your manager ?',
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                        ],
                    ],
                ],
            ])?>
        </div>
    </div>
</div>
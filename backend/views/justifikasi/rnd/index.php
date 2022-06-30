<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models]JustifikasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Justifikasi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Approve</div>
            </div>
            <div class="card-body table-responsive p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
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
                            'header' => 'Approve',
                            'class' => ActionColumn::className(),
                            'template' => '{approve}',
                            'buttons' => [
                                'approve' => function ($url, $model) {
                                    return Html::a('detail', $url, ['class' => 'btn btn-success btn-xs']);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action == 'approve') {
                                    return Url::to(['approvernd', 'id' => $model->id]);
                                }
                            }
                        ],
                    ],
                ])?>
            </div>
        </div>
    </div>
</div>
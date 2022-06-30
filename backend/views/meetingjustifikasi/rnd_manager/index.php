<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingjustifikasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Review';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="justifikasi-index-approve">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title">List Review Justifikasi</div>
                </div>
                <div class="card-body table-responsive p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'table table-striped',
                        ],
                        'layout' => '{items}{pager}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'header' => 'Id Transaksi',
                                'value' => function ($model) {
                                    return $model->registrasi->id_transaksi;
                                }
                            ],
                            [
                                'header' => 'Nama Produk',
                                'value' => function ($model) {
                                    return $model->registrasi->nama_produk;
                                }
                            ],
                            [
                                'header' => 'Variasi',
                                'value' => function ($model) {
                                    return $model->kategori->jenisMeeting->deskripsi.' : '.$model->kategori->deskripsi;
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
                                        return Url::to(['approverndmanager', 'id' => $model->id]);
                                    }
                                }
                            ],
                        ],
                    ])?>
                </div>
            </div>
        </div>
    </div>
</div>
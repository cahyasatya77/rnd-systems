<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TindakanSearch */
/* @var $dataProvider yii\data\ActionDataProvider */

$this->title = 'Approve Tagihan Meeting';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="approve-tagihan-meeting">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">List Approve</div>
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
                    'header' => 'Nama Produk',
                    'value' => function ($data) {
                        return $data->kategori->meeting->nama_produk;
                    }
                ],
                [
                    'header' => 'Kategori',
                    'value' => function ($data) {
                        return $data->kategori->jenisMeeting->deskripsi;
                    }
                ],
                'dokumen',
                [
                    'attribute' => 'pic',
                    'value' => function ($data) {
                        return $data->user->username;
                    }
                ],
                'deadline',
                [
                    'header' => 'Lampiran',
                    'value' => function ($data) {
                        if ($data->nama_dok != null) {
                            return Html::a($data->nama_dok, ['download', 'id' => $data->id]);
                        } else {
                            return '<strong class="text-red">Lampiran belum terupload</strong>';
                        }
                    },
                    'format' => 'raw',
                ],

                [
                    'header' => 'Approve',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{justif}',
                    'buttons' => [
                        'justif' => function ($url, $model) {
                            $url = Url::to(['approve', 'id' => $model->id]);
                            return Html::a('detail', $url, ['class' => 'btn btn-success btn-xs']);
                        }
                    ],
                ],
            ],
        ])?>
    </div>
    </div>
</div>
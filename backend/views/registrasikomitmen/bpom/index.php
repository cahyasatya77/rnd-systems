<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistrasikomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Komitmen Terpenuhi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-title">Komitmen</div>
        <div class="float-right"><?= Html::a('History', ['komitmenbpom/history'], ['class' => 'btn btn-info btn-xs'])?></div>
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
                    'header' => 'ID Komitmen',
                    'value' => function($model) {
                        return $model->komitmen->id_transaksi;
                    }
                ],
                [
                    'header' => 'Nama Obat',
                    'value' => function ($model) {
                        return $model->komitmen->nama_obat;
                    }
                ],
                [
                    'header' => 'Komitmen',
                    'value' => function ($model) {
                        return $model->jenis->jenis;
                    }
                ],
                [
                    'header' => 'Status',
                    // 'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->status;
                    }
                ],

                [
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{close}',
                    'buttons' => [
                        'close' => function ($url, $model) {
                            $url = Url::to(['closekomitmen', 'id' => $model->id]);
                            return Html::a('done', $url, ['class' => 'btn btn-success btn-xs']);
                        }
                    ],
                ]
            ],
        ])?>
    </div>
</div>

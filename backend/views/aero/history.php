<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingregistrasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'History New Aero';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-title">List history</div>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-default btn-xs float-right'])?>
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
                    'header' => 'ID',
                    'attribute' => 'id_transaksi'
                ],
                'nama_produk',
                'ed_nie',
                'submit_aero',
                [
                    'header' => 'Status',
                    'value' => function ($model) {
                        return $model->status->deskripsi;
                    }
                ],

                [
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                        }
                    ],
                ],
            ],
        ]);?>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingregistrasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'New Aero';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-title">List submit to New Aero</div>
        <?= Html::a('History', ['history'], ['class' => 'btn btn-info btn-xs float-right'])?>
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
                    'template' => '{close}',
                    'buttons' => [
                        'close' => function ($url, $model) {
                            $url = Url::to(['close', 'id' => $model->id]);
                            return Html::a('close', $url, ['class' => 'btn btn-success btn-xs']);
                        }
                    ],
                ],
            ],
        ]);?>
    </div>
</div>

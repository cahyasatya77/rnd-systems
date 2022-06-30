<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistrasikomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Approve Komitmen';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <div class="card-title">Komitmen Registrasi</div>
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
                ['class' => 'yii\grid\SerialColumn'],

                'nama_obat',
                'bentuk_sediaan',
                'tanggal_pembuatan',
                [
                    'header' => 'Status',
                    'attribute' => 'status.deskripsi',
                ],

                [
                    'header' => 'Approve',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{approve}',
                    'buttons' => [
                        'approve' => function ($url, $model) {
                            $url = Url::to(['detailapprove', 'id' => $model->id]);
                            return Html::a('Approve', $url, ['class' => 'btn btn-success btn-xs']);
                        }
                    ],
                ],
            ],
        ]);?>
    </div>
</div>
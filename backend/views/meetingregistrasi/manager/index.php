<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Meetingregistrasi */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $produk yii\db\Query */

$this->title = 'Approve Meeting Registasi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-title">Notulen Meeting Registasi</div>
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
                    'header' => 'Tanggal Pembuatan',
                    'value' => function ($data) {
                        return date('d F Y', strtotime($data->tanggal_pembuatan));
                    }
                ],
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
                            $url = Url::to(['approve', 'id' => $model->id]);
                            return Html::a('Approve', $url, ['class' => 'btn btn-success btn-xs']);
                        }
                    ],
                ],
            ],
        ]);?>
    </div>
</div>
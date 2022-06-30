<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingregistrasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Meeting Registrasi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <!-- Search GridView -->
    <div class="col-md-12">
        <?= $this->render('_search', [
            'model' => $searchModel,
            'produk' => $produk,
            'status' => $status,
        ]);?>
    </div>
    <!-- GridView -->
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
            </div>
            <div class="card-body p-0">
                 <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'tableOptions' => [
                        'class' => 'table table-striped',
                    ],
                    'layout' => '{items}{pager}',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id_transaksi',
                        'nama_produk',
                        'submit_aero',
                        [
                            'header' => 'Status',
                            'value' => 'status.deskripsi',
                        ],

                        // Actions
                        [
                            'header' => 'View',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                                }
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistrasikomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Komitmen Registrasi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registrasikomitmen-index-pic">
    <!-- Search GridView -->
    <?= $this->render('_search', [
        'model' => $searchModel,
        'produk' => $produk,
        'status' => $status,
    ]);?>
    <!-- GridView -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Komitmen</div>
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

                    'id_transaksi',
                    'nama_obat',
                    'bentuk_sediaan',
                    [
                        'label' => 'Status',
                        'attribute' => 'status.deskripsi',
                    ],

                    [
                        'header' => 'Detail',
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
</div>
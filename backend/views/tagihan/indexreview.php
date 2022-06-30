<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TagihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Review Komitmen';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="komitmen-index-review">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">List detail review tagihan komitmen</div>
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
                        'header' => 'Nama Obat',
                        'value' => function ($data) {
                            return $data->jnsKomitmen->komitmen->nama_obat;
                        }
                    ],
                    [
                        'header' => 'Komitmen',
                        'attribute' => 'id_jns_komitmen',
                        'value' => function ($data) {
                            return $data->jnsKomitmen->jenis->jenis;
                        }
                    ],
                    'dokumen',
                    [
                        'attribute' => 'pic',
                        'value' => function ($data) {
                            return $data->user->username;
                        }
                    ],
                    'dead_line',

                    [
                        'header' => 'Review',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{review}',
                        'buttons' => [
                            'review' => function ($url, $model) {
                                $url = Url::to(['reviewrnd', 'id' => $model->id]);
                                return Html::a('review', $url, ['class' => 'btn btn-success btn-xs']);
                            }
                        ],
                    ],
                ],
            ]);?>
        </div>
    </div>
</div>
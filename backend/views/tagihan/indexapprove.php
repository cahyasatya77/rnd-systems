<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\Web */
/* @var $searchModel backend\models\TindakanSearch */
/* @var $dataProvider yii\data\ActionDataProvider */

$this->title = 'Komitmen Registrasi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="komitmen-index-approve">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Approve Komitmen Tindakan</div>
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
            ]);?>
        </div>
    </div>
</div>
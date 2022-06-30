<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MasterjeniskomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="masterjeniskomitmen-index">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Master Jenis Komitmen</h3>
            <div class="card-tools">
                <?= Html::a('Create', ['create'], ['class' => 'btn btn-success btn-sm'])?>
            </div>
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

                    'jenis',

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
                    [
                        'header' => 'Update',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('update', $url, ['class' => 'btn btn-warning btn-xs']);
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-title">Section Department</div>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::a('Create', ['create'], ['class' => 'btn btn-success btn-sm'])?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    [
                        'header' => 'No.',
                        'class' => 'yii\grid\SerialColumn'
                    ],

                    'id',
                    [
                        'label' => 'Department',
                        'attribute' => 'id_dept',
                        'value' => 'dept.nama_dept'
                    ],
                    'name_section',
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return date('Y-m-d H:i:s', $model->created_at);
                        }
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => function ($model) {
                            return date('Y-m-d H:i:s', $model->updated_at);
                        }
                    ],

                    [
                        'header' => 'Update',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function($url, $model) {
                                return Html::a('update', $url, ['class' => 'btn btn-warning btn-xs']);
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

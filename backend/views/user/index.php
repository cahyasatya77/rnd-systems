<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataPrivider */

use common\models\Department;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Users Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-title">Users</div>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::a('Create', ['signup'], ['class' => 'btn btn-success btn-sm'])?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    [
                        'header' => 'Department',
                        'value' => function ($model) {
                            $dept = Department::findOne($model->id_dept);
                            return $dept->nama_dept;
                        }
                    ],

                    [
                        'header' => 'View',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                            }
                        ],
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
            ]);?>
        </div>
    </div>
</div>
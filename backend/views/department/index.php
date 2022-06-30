<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="row">
            <div class="col-md-6">
                <div class="card-title">Department</div>
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
                    'class' => 'yii\grid\SerialColumn',
                ],

                'id',
                'nama_dept',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update}'
                ],
            ],
        ]); ?>
        </div>
    </div>
</div>

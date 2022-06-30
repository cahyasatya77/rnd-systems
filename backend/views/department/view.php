<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Department */

$this->title = $model->nama_dept;
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="department-view">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Detail Department</div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'nama_dept',
                ],
            ]) ?>
        </div>
        <div class="card-footer">
            <?= Html::a('Index', ['index'], ['class' => 'btn btn-default'])?>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjenismeetingreg */

$this->title = $model->deskripsi;
$this->params['breadcrumbs'][] = ['label' => 'index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="masterjenismeetingreg-view">
    <div class="card card-outline card-info">
        <div class="card-header">
            <div class="card-title">View</div>
            <div class="float-right">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= DetailView::widget([
                'model' => $model,
                'options' => [
                    'class' => 'table table-striped'
                ],
                'attributes' => [
                    'deskripsi',
                    'created_by',
                    'created_at',
                ],
            ]) ?>
        </div>
    </div>
</div>

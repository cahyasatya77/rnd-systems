<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = 'Update Section: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="section-update">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <div class="card-title">Update Section</div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
            'dept' => $dept,
        ]) ?>
    </div>
</div>

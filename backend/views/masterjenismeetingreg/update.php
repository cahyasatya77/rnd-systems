<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjenismeetingreg */

$this->title = 'Update : ' . $model->deskripsi;
$this->params['breadcrumbs'][] = ['label' => 'Master Jenis MR', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="masterjenismeetingreg-update">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <div class="card-title">Update</div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjeniskomitmen */

$this->title = 'Update : ' . $model->jenis;
$this->params['breadcrumbs'][] = ['label' => 'Masterjeniskomitmens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="masterjeniskomitmen-update">
    <div class="card card-outline card-warning">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

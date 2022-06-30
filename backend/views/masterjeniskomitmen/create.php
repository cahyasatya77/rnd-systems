<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjeniskomitmen */

$this->title = 'Create Jenis Komitmen';
$this->params['breadcrumbs'][] = ['label' => 'Masterjeniskomitmens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="masterjeniskomitmen-create">
    <div class="card card-outline card-success">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

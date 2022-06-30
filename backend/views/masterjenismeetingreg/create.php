<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjenismeetingreg */

$this->title = 'Create Master Jenis Meeting';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="masterjenismeetingreg-create">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Create</div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

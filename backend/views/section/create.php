<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = 'Create Section';
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-create">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Create Section</div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
            'dept' => $dept,
        ]) ?>
    </div>
</div>

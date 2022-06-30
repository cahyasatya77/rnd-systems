<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Masterdokumen */

$this->title = 'Update : ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Master Dokumen', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="masterdokumen-update">
    <div class="card card-outline card-warning">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

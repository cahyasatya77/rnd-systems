<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Masterdokumen */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Master Dokumen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="masterdokumen-create">
    <div class="card card-outline card-success">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

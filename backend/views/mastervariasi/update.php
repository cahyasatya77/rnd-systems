<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Mastervariasi */

$this->title = 'Update : ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Variasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mastervariasi-update">
    <?= $this->render('_form', [
        'model' => $model,
        'model_detail' => $model_detail,
        'dokumen' => $dokumen,
    ]) ?>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Mastervariasi */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Kategori Variasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mastervariasi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'model_detail' => $model_detail,
        'dokumen' => $dokumen,
    ]) ?>

</div>

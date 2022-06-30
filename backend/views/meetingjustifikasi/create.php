<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingjustifikasi */

$this->title = 'Create Justifikasi';
$this->params['breadcrumbs'][] = ['label' => 'index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meetingjustifikasi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'model_detail' => $model_detail,
        'model_meeting' => $model_meeting,
        'model_kategori' => $model_kategori,
        'data_dokumen' => $data_dokumen,
        'user_pic' => $user_pic,
        'model_dokumen' => $model_dokumen
    ]) ?>

</div>

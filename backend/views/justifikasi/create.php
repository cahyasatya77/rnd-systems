<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Justifikasi */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Justifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="justifikasi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'model_detail' => $model_detail,
        'model_produk' => $model_produk,
        'jenis_komitmen' => $jenis_komitmen,
        'data_komitmen' => $data_komitmen,
        'user_pic' => $user_pic,
        'tindakan' => $tindakan,
    ]) ?>

</div>

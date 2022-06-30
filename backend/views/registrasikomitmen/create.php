<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Registrasikomitmen */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'komitmen registrasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasikomitmen-create">
    
        <?= $this->render('_form', [
            'model' => $model,
            'model_jenis' => $model_jenis,
            'model_komitmen' => $model_komitmen,
            'produk' => $produk,
            'pic' => $pic,
            'jenis_komitmen' => $jenis_komitmen,
        ]) ?>
  
</div>

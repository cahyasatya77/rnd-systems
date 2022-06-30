<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Registrasikomitmen */

$this->title = 'Update : ' . $model->nama_obat;
$this->params['breadcrumbs'][] = ['label' => 'Registrasikomitmens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_obat, 'url' => ['view', 'id' => $model->nama_obat]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="registrasikomitmen-update">

    <?php if ($model->status == 'revisi') : ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Catatan Revisi!</h5>
            <?= $model->note_revisi; ?>.
        </div>
    <?php endif;?>

    <?= $this->render('_form', [
        'model' => $model,
        'model_jenis' => $model_jenis,
        'model_komitmen' => $model_komitmen,
        'produk' => $produk,
        'pic' => $pic,
        'jenis_komitmen' => $jenis_komitmen,
    ]) ?>

</div>

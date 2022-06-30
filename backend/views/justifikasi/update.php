<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Justifikasi */

$this->title = 'Update : ' . $model_produk->nama_obat;
$this->params['breadcrumbs'][] = ['label' => 'Justifikasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_produk->nama_obat, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="justifikasi-update">

    <div class="row">
    <?php if ($model->note_revisi != null):?>
        <div class="col-md-4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Revisi PIC Manager !</h5>
                <?= $model->note_revisi; ?>
            </div>
        </div>
    <?php endif;?>
    <?php if ($model->revisi_rnd != null):?>
        <div class="col-md-4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Revisi R&D Registasi !</h5>
                <?= $model->revisi_rnd; ?>
            </div>
        </div>
    <?php endif;?>
    <?php if ($model->revisi_rnd_manager != null):?>
        <div class="col-md-4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Revisi R&D Manager !</h5>
                <?= $model->revisi_rnd_manager; ?>
            </div>
        </div>
    <?php endif;?>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'model_detail' => $model_detail,
        'model_produk' => $model_produk,
        'jenis_komitmen' => $jenis_komitmen,
        'data_komitmen' => $data_komitmen,
        'user_pic' => $user_pic,
    ]) ?>

</div>

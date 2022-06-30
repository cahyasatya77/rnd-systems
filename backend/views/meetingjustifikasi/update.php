<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingjustifikasi */

$this->title = 'Update Justifikasi';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->registrasi->nama_produk, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="meetingjustifikasi-update">

<div class="row">
    <?php if ($model->revisi_pic_manager != null) :?>
        <div class="col-md-4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i>Revisi PIC Manager !</h5>
                <?= $model->revisi_pic_manager; ?>
            </div>
        </div>
    <?php endif;?>
    <?php if ($model->revisi_rnd != null) :?>
        <div class="col-md-4">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i>Revisi PIC Manager !</h5>
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
        'model_meeting' => $model_meeting,
        'model_kategori' => $model_kategori,
        'data_dokumen' => $data_dokumen,
        'user_pic' => $user_pic,
    ]) ?>

</div>

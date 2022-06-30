<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MeetingjustifikasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meetingjustifikasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_registrasi') ?>

    <?= $form->field($model, 'id_kategori') ?>

    <?= $form->field($model, 'alasan_justif') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'pic_manager') ?>

    <?php // echo $form->field($model, 'pic_manager_approve') ?>

    <?php // echo $form->field($model, 'rnd_approve') ?>

    <?php // echo $form->field($model, 'rnd_manager_approve') ?>

    <?php // echo $form->field($model, 'revisi_pic_manager') ?>

    <?php // echo $form->field($model, 'revisi_rnd') ?>

    <?php // echo $form->field($model, 'revisi_rnd_manager') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

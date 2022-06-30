<?php

use yii\bootstrap4\ActiveForm;
// use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistasi */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Revisi : '.$model->nama_produk;
$this->params['breadcrumbs'][] = ['label' => 'Detail Approve', 'url' => ['approve', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => 'index', 'url' => ['indexmanager']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="meetingregistasi-form-revisi">
    <?php $form = ActiveForm::begin()?>
    <div class="card card-outline card-warning">
        <div class="card-header">
            <div class="card-title">Form Revisi</div>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'note_revisi')->textarea(['rows' => 6]);?>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Back', ['approve', 'id' => $model->id], ['class' => 'btn btn-danger'])?>
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-success float-right'])?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>
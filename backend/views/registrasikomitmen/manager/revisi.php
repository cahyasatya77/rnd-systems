<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RegistrasiKomitmen */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Revisi : '.$model->nama_obat;
$this->params['breadcrumbs'][] = ['label' => 'Detail Approve', 'url' => ['detailapprove', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="registrasikomitmen-form-revisi">
    <?php $form = ActiveForm::begin();?>
    <div class="card card-outline card-warning">
        <div class="card-header">
            <div class="card-title">Form Revisi</div>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'note_revisi')->textarea();?>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Back', ['detailapprove', 'id' => $model->id], ['class' => 'btn btn-danger'])?>
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-success float-right'])?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>
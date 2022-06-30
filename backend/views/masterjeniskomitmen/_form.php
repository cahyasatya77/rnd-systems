<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjeniskomitmen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="masterjeniskomitmen-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
        <?= $form->field($model, 'jenis')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
            </div>
            <div class="col-md-6 text-right">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

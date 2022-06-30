<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjenismeetingreg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="masterjenismeetingreg-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
        <?= $form->field($model, 'deskripsi')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success float-right']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

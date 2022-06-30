<?php

use kartik\select2\Select2;
use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Section */
/* @var $form yii\widgets\ActiveForm */

$list_dept = ArrayHelper::map($dept, 'id', 'nama_dept');
?>

<div class="section-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
        <?= $form->field($model, 'id_dept')->widget(Select2::className(), [
            'data' => $list_dept,
            'options' => [
                'placeholder' => '- Pilih department -'
            ],
        ]) ?>

        <?= $form->field($model, 'name_section')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

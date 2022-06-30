<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Masterdokumen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="masterdokumen-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card-body">
        <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->widget(Select2::className(), [
            'data' => [
                'active' => 'Active',
                'in-active' => 'In-Active',
            ],
        ])?>
    </div>
    <div class="card-footer">
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

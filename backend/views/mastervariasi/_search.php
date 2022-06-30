<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MastervariasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mastervariasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="card card-outline card-default">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'kode', [
                        'inputOptions' => [
                            'placeholder' => '- Kode Variasi -'
                        ],
                    ])->textInput()->label(false)?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'deskripsi', [
                        'inputOptions' => [
                            'placeholder' => '- Deskripsi -'
                        ],
                    ])->textInput()->label(false)?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'status', [
                        'inputOptions' => [
                            'placeholder' => '- Status -'
                        ],
                    ])->widget(Select2::className(), [
                        'data' => [
                            'active' => 'Active',
                            'in-active' => 'in-active',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(false)?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

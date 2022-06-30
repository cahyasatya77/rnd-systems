<?php

use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MasterdokumenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="masterdokumen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="card card-default">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'kode', [
                        'inputOptions' => [
                            'placeholder' => '- Kode Dokumen -'
                        ]
                    ])->textInput()->label(false) ?>
                </div>
                <div class="col-md-7">
                    <?= $form->field($model, 'deskripsi', [
                        'inputOptions' => [
                            'placeholder' => '- Deskripsi Dokumen -'
                        ],
                    ])->textInput()->label(false)?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

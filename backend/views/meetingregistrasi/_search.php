<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\MeetingregistrasiSearch */
/* @var $form yii\widgets\ActiveForm */

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
$list_status = ArrayHelper::map($status, 'id', 'deskripsi');
?>

<div class="meetingregistrasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <?= $form->field($model, 'id_produk')->widget(Select2::className(), [
                    'data' => $list_produk,
                    'options' => [
                        'placeholder' => '- Pilih Produk -'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(false) ?>
            </div>
            <div class="col-md-5">
                <?= $form->field($model, 'id_status')->widget(Select2::className(), [
                    'data' => $list_status,
                    'options' => [
                        'placeholder' => '- Pilih status -'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(false) ?>
            </div>
            <div class="col-md-2">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

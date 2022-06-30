<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\RegistrasikomitmenSearch */
/* @var $form yii\widgets\ActiveForm */

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
?>

<div class="registrasikomitmen-search">
    
    <div class="card card-outline card-default">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="card-header">
            <div class="card-title">Search</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($model, 'id_obat')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Produk -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(false) ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'id_status', [
                        'inputOptions' => [
                            'placeholder' => '- Isikan Status Komitmen -',
                        ]
                    ])->label(false) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

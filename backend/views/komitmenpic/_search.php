<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RegistrasikomitmenSearch */
/* @var $form yii\widgets\ActiveForm */

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
$list_status = ArrayHelper::map($status, 'id', 'deskripsi');
?>

<div class="komitmenpic-search">
    <div class="card card-outline card-default">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ])?>

        <div class="card-header">
            <div class="card-title">Search</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($model, 'id_obat')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Produk -'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label(false)?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'id_status')->widget(Select2::className(), [
                        'data' => $list_status,
                        'options' => [
                            'placeholder' => '- Pilih Status -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(false)?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Seach', ['class' => 'btn btn-primary btn-block'])?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>
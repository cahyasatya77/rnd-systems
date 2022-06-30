<?php

/* @var $this yii\web\View */
/* @var $model_kategori common\models\Meetingkategori */
/* @var $model_dokumen common\models]Meetingdokumen */

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$list_pic = ArrayHelper::map($pic, 'id', 'username');
?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-dok',
    'widgetItem' => '.dok-item',
    'min' => 1,
    'insertButton' => '.add-dok',
    'deleteButton' => '.remove-dok',
    'model' => $model_dokumen[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'id',
    ],
])?>

<div class="container-dok">
    <?php foreach($model_dokumen as $d => $dokumen) :?>
        <div class="dok-item">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Dokumen Mutu</div>
                            <div class="float-right">
                                <button type="button" class="add-dok btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                                <button type="button" class="remove-dok btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                    // necessary for update action.
                                    if (!$dokumen->isNewRecord) {
                                        echo Html::activeHiddenInput($dokumen, "[{$i}{$d}]id");
                                    } 
                                ?>
                                <div class="col-md-12">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]dokumen")->textarea();?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]pic")->widget(Select2::className(), [
                                        'data' => $list_pic,
                                        'options' => [
                                            'placeholder' => '- Pilih PIC -',
                                        ],
                                    ]);?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]deadline")->widget(DatePicker::className(), [
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                        ],
                                    ])?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>

<?php DynamicFormWidget::end();?>
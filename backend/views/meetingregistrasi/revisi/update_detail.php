<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$list_pic = ArrayHelper::map($pic, 'id', 'username');
$list_dok = ArrayHelper::map($variasi_dok, 'id', 'kode');
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
        'dokumen',
        'pic',
        'deadline'
    ],
]);?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="row text-center">
                    <div class="col-md-5">Dokumen</div>
                    <div class="col-md-2">Catatan</div>
                    <div class="col-md-2">PIC</div>
                    <div class="col-md-2">Deadline</div>
                    <div class="col-md-1">Action</div>
                </div>
            </div>
            <div class="card-body">
                <div class="container-dok">
                    <?php foreach($model_dokumen as $d => $dokumen) :?>
                        <div class="dok-item">
                            <?php 
                                // necessary for update action.
                                if (!$dokumen->isNewRecord) {
                                    echo Html::activeHiddenInput($dokumen, "[{$i}][{$d}]id");
                                }
                            ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]id_dokumen")->widget(Select2::className(), [
                                        'data' => $list_dok,
                                        'options' => [
                                            'placeholder' => '- Pilih Dokumen -',
                                        ],
                                    ])->label(false)?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]note_rnd")->textInput()->label(false)?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]pic")->widget(Select2::className(), [
                                        'data' => $list_pic,
                                        'options' => [
                                            'placeholder' => '- Pilih PIC -'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label(false)?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($dokumen, "[{$i}][{$d}]deadline")->widget(DatePicker::className(), [
                                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                        'removeButton' => false,
                                        'options' => [
                                            'placeholder' => '- Input Deadline -',
                                        ],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true
                                        ],
                                    ])->label(false)?>
                                </div>
                                <div class="col-md-1 pt-1 text-center">
                                    <button type="button" class="add-dok btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                                    <button type="button" class="remove-dok btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php DynamicFormWidget::end();?>
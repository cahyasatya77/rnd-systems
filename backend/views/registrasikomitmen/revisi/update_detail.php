<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$list_pic = ArrayHelper::map($pic, 'id', 'username');
?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-kom',
    'widgetItem' => '.kom-item',
    'min' => 1,
    'insertButton' => '.add-kom',
    'deleteButton' => '.remove-kom',
    'model' => $model_komitmen[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'id'
    ], 
]);?>

<div class="container-kom">
    <?php foreach($model_komitmen as $k => $komitmen) :?>
        <div class="kom-item">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Detail Komitmen</div>
                    <div class="float-right">
                        <button type="button" class="add-kom btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                        <button type="button" class="remove-kom btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                            echo Html::activeHiddenInput($komitmen, "[{$i}][{$k}]id");
                        ?>
                        <div class="col-md-12">
                            <?= $form->field($komitmen, "[{$i}][{$k}]dokumen")->textarea();?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($komitmen, "[{$i}][{$k}]jenis_dokumen")->widget(Select2::className(),[
                                'data' => [
                                    'Dokumen Submit' => 'Dokumen Submit',
                                    'Dokumen Pendukung' => 'Dokumen Pendukung', 
                                ],
                                'options' => [
                                    'placeholder' => '- Pilih Jenis Dokumen -'
                                ],
                            ])?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($komitmen, "[{$i}][{$k}]pic")->widget(Select2::className(),[
                                'data' => $list_pic,
                                'options' => [
                                    'placeholder' => '- Pilih PIC -'
                                ],
                            ])?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($komitmen, "[{$i}][{$k}]dead_line")->widget(DatePicker::className(), [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ],
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>

<?php DynamicFormWidget::end();?>
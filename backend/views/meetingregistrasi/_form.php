<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $form yii\widgets\ActiveForm */

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
?>

<div class="meetingregistrasi-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'dynamic-form',
            'class' => 'disable-submit-buttons',  
        ],
    ]); ?>

    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Create Notulen</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'id_produk')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Produk -',
                            'id' => 'produk',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'ed_nie')
                            ->textInput([
                                'maxlength' => true, 
                                'readonly' => true,
                                'id' => 'ed',
                            ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'submit_aero')->widget(DatePicker::className(), [
                        'options' => [
                            'placeholder' => '- Pilih Tanggal -'
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="divider-text">Kategori Registrasi Variasi</div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $model_kategori[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'id'
        ],
    ]);?>

    <div class="container-items">
        <?php foreach ($model_kategori as $i => $kategori) :?>
            <div class="item">
                <div class="row">
                    <div class="col-md-1" style="display: table-cell; float: none; height:auto;">
                        <h6 class="number" style="position: absolute; bottom: 2em; left: 1em;"><?= 'No : '.($i+1)?></h6>
                    </div>
                    <div class="col-md-10">
                        <?php
                            // necessary for update action.
                            if (!$kategori->isNewRecord) {
                                echo Html::activeHiddenInput($kategori, "[{$i}]id");
                            }
                        ?>

                        <?= $form->field($kategori, "[{$i}]deskripsi")->textarea(['rows' => 2])->label('Variasi');?>
                    </div>
                    <div class="col-md-1 text-center">
                        <label>Action</label>
                        <div class="text-center">
                            <button type="button" class="add-item btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                        </div>
                    </div>
                </div>

                <?= $this->render('_form_detail', [
                    'form' => $form,
                    'i' => $i,
                    'model_dokumen' => $model_dokumen[$i],
                    'pic' => $pic,
                ]);?>

            </div>
        <?php endforeach;?>
    </div>

    <?php DynamicFormWidget::end();?>
    

    <div class="card card-outline card-defaut">
        <div class="card-footer">
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger']);?>
            <?= Html::submitButton('Save', [
                'class' => 'btn btn-success float-right',
                'data' => ['disabled-text' => 'Validating ...']
            ])?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$script = <<<JS
    $('#produk').change(function() {
        var id = $(this).val();
        $.get('ednie', {id:id}, function(data){
            var data = $.parseJSON(data);
            $('#ed').attr('value', data['ed_nie']);
        });
    });

    jQuery('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html('No : ' + (index + 1));
        });
    });

    jQuery('.dynamicform_wrapper').on('afterDelete', function(e) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html('No : ' + (index + 1))
        });
    });

JS;
$this->registerJs($script);
$this->registerCssFile('@web/css/cahya.css');
?>
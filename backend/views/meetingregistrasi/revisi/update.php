<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $model_kategori common\models\Meetingkategori */
/* @var $model_dokumen common\models\Meetingdokumen */

$this->title = 'Revisi';
$this->params['breadcrumbs'][] = ['label' => 'List Revisi', 'url' => ['indexrevisi']];
$this->params['breadcrumbs'][] = $this->title;

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
$list_jenis = ArrayHelper::map($jenis_meeting, 'id', 'deskripsi');
$list_variasi = ArrayHelper::map($variasi, 'id', 'kode');
?>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5>
                <i class="icon icon fas fa-exclamation-triangle"></i> Note Revisi
            </h5>
            <?= $model->note_revisi;?>
        </div>
    </div>
</div>

<div class="meetingregistrasi-revisi">
<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'dynamic-form',
        'class' => 'disable-submit-buttons',
    ],
])?>

    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Update Notulen Meeting</div>
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
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-1 text-center">
                                <label>No.</label>
                                <h6 class="number pt-2"><?= ($i+1)?></h6>
                            </div>
                            <?php
                                // necessary for update action.
                                if (!$kategori->isNewRecord) {
                                    echo Html::activeHiddenInput($kategori, "[{$i}]id");
                                }
                            ?>
                            <div class="col-md-2">
                                <?= $form->field($kategori, "[{$i}]id_jenis_meeting")->widget(Select2::className(), [
                                    'data' => $list_jenis,
                                    'options' => [
                                        'placeholder' => '- Pilih Jenis Meeting -',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label('Jenis Notulen')?>
                            </div>
                            <div class="col-md-8">
                                <?= $form->field($kategori, "[{$i}]id_variasi")->widget(Select2::className(), [
                                    'data' => $list_variasi,
                                    'options' => [
                                        'placeholder' => '- Pilih Kode Variasi -'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label('Deskripsi');?>
                            </div>
                            <div class="col-md-1 text-center">
                                <label>Action</label>
                                <div class="text-center">
                                    <button type="button" class="add-item btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?= $this->render('update_detail', [
                            'form' => $form,
                            'i' => $i,
                            'model_dokumen' => $model_dokumen[$i],
                            'pic' => $pic,
                            'variasi_dok' => $variasi_dok,
                        ]);?>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>

    <?php DynamicFormWidget::end();?>
    

    <div class="card card-outline card-default">
        <div class="card-footer">
            <?= Html::a('Back', ['indexrevisi'], ['class' => 'btn btn-danger']);?>
            <?= Html::submitButton('Save', [
                'class' => 'btn btn-success float-right',
                'data' => ['disabled-text' => 'Validating....']
            ])?>
        </div>
    </div>

<?php ActiveForm::end();?>
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
            jQuery(this).html((index + 1));
        });
    });

    jQuery('.dynamicform_wrapper').on('afterDelete', function(e) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html((index + 1))
        });
    });

JS;
$this->registerJs($script);
$this->registerCssFile('@web/css/cahya.css');
?>
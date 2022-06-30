<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingjustifikasi */
/* @var $form yii\widgets\ActiveForm */

$list_dokumen = ArrayHelper::map($data_dokumen, 'id', 'dokumen');
$list_pic = ArrayHelper::map($user_pic, 'id', 'username');
?>

<div class="meetingjustifikasi-form">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Detail Notulen Meeting</div>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model_meeting,
                                'options' => [
                                    'class' => 'table detail-view',
                                ],
                                'attributes' => [
                                    'id_transaksi',
                                    'nama_produk',
                                    'keterangan',
                                ],
                            ])?>
                        </div>
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model_meeting,
                                'options' => [
                                    'class' => 'table detail-view'
                                ],
                                'attributes' => [
                                    'ed_nie',
                                    [
                                        'label' => 'Submit new AERO',
                                        'value' => function ($data) {
                                            return '<div class="p-2 bg-warning">'.date('d F Y', strtotime($data->submit_aero)).'</div>';
                                        },
                                        'format' => 'raw',
                                    ],
                                    [
                                        'label' => $model_kategori->jenisMeeting->deskripsi,
                                        'value' => $model_kategori->deskripsi,
                                    ],
                                ],
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'dynamic-form',
            'class' => 'disable-submit-buttons'
        ],
    ]); ?>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Justifikasi</div>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'alasan_justif')->textarea(['rows' => 3])?>

                <?= $form->field($model, 'pic_manager')->widget(Select2::className(), [
                    'data' => $list_pic,
                    'options' => [
                        'placeholder' => '- Pilih Manager Tujuan -'
                    ],
                ])?>
            </div>
        </div>

        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.container-items',
            'widgetItem' => '.item',
            'min' => 1,
            'insertButton' => '.add-item',
            'deleteButton' => '.remove-item',
            'model' => $model_detail[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'id',
            ],
        ])?>

            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-title">Dokumen</div>
                        </div>
                        <div class="col-md-2">
                            <div class="card-title">Deadline</div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-title">New Deadline</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-items">
                        <?php foreach($model_detail as $i => $detail) :?>
                            <div class="item main-form">
                                <?php
                                    if (!$detail->isNewRecord) {
                                        echo Html::activeHiddenInput($detail, "[{$i}]id");
                                    }
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($detail, "[{$i}]id_dokumen")->widget(Select2::className(), [
                                            'data' => $list_dokumen,
                                            'options' => [
                                                'placeholder' => '- Pilih Dokumen Terdampak -',
                                                'class' => 'dok',
                                            ],
                                        ])->label(false)?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($detail, "[{$i}]deadline_old")->textInput([
                                            'readonly' => true,
                                            'class' => 'form-control deadline',
                                        ])->label(false)?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($detail, "[{$i}]deadline_new")->widget(DatePicker::className(), [
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd',
                                            ],
                                        ])->label(false)?>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="add-item btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                                        <button type="button" class="remove-item btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>

        <?php DynamicFormWidget::end();?>

        <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-footer">
                    <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-danger'])?>
                    <?= Html::submitButton('Save', [
                        'class' => 'btn btn-success float-right',
                        'data' => ['disabled-text' => 'Validating ...']
                    ])?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$baseUrl = Json::encode(Yii::$app->urlManager->baseUrl.'/meetingjustifikasi');
$script = <<<JS
    $(document).on('change', '.dok', function() {
        var current = $(this);
        var parent = current.parents('.main-form');
        var kode = current.val();

        $.get($baseUrl + '/deadline', {kode:kode}, function(data) {
            var data = $.parseJSON(data);
            // alert(data.deadline);
            parent.find('.deadline').val(data.deadline);
        });
    });
JS;
$this->registerJs($script);

if ($model->isNewRecord) {
    $dok = Json::encode($model_dokumen->id);
    $js = <<<JS
        $(document).ready(function() {
            // alert($dok);
            $("#meetingjustifikasidetail-0-id_dokumen").val($dok).trigger('change');
        });
    JS;
    $this->registerJs($js);
}

$this->registerCssFile('@web/css/cahya.css');
?>

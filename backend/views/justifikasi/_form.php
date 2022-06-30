<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model common\models\Justifikasi */
/* @var $form yii\widgets\ActiveForm */

$list_komitmen = ArrayHelper::map($data_komitmen, 'id', 'dokumen');
$list_pic = ArrayHelper::map($user_pic, 'id', 'username');
?>

<div class="justifikasi-form">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Detail Komitmen</div>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model_produk,
                                'options' => [
                                    'class' => 'table detail-view',
                                ],
                                'attributes' => [
                                    'id_transaksi',
                                    'nama_obat',
                                    'bentuk_sediaan'
                                ],
                            ])?>
                        </div>
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $jenis_komitmen,
                                'options' => [
                                    'class' => 'table detail-view',
                                ],
                                'attributes' => [
                                    [
                                        'label' => 'Komitmen',
                                        'value' => $jenis_komitmen->jenis->jenis,
                                    ],
                                    [
                                        'label' => 'Deadline Komitmen',
                                        'value' => function ($data) {
                                            return '<div class="p-2 bg-warning">'.date('d F Y', strtotime($data->deadline_komit)).'</div>';
                                        },
                                        'format' => 'raw',
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
        'id' => 'dynamic-form',
    ]); ?>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Justifikasi</div>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'alasan_justif')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'pic_manager')->widget(Select2::className(), [
                'data' => $list_pic,
                'options' => [
                    'placeholder' => '- Pilih Manager Tujuan -',
                ],
            ]) ?>
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
            'id'
        ],
    ]);?>

    <!-- <div class="container-items"> -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-title">Komitmen Terdampak</div>
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
                                <?= $form->field($detail, "[{$i}]id_kom_tindakan")->widget(Select2::className(), [
                                    'data' => $list_komitmen,
                                    'options' => [
                                        'placeholder' => '- Pilih Dokumen terdampak -',
                                        // 'value' => $detail->isNewRecord ? $model_komitmen->id : $detail->id_kom_tindakan,
                                        'class' => 'komit'
                                    ],
                                ])->label(false);?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($detail, "[{$i}]deadline_old")->textInput([
                                    'readonly' => true,
                                    // 'value' => $detail->isNewRecord ? $model_komitmen->dead_line : $detail->deadline_old,
                                    'class' => 'form-control deadline'
                                ])->label(false)?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($detail, "[{$i}]deadline_new")->widget(DatePicker::className(), [
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ],
                                ])->label(false);?>
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
    <!-- </div> -->

    <?php DynamicFormWidget::end()?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-danger'])?>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success float-right'])?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$baseUrl = Json::encode(Yii::$app->urlManager->baseUrl);
$script = <<<JS
    $(document).on('change', '.komit', function(){
        var current = $(this);
        var parent = current.parents('.main-form');
        var kode = current.val();

        $.get($baseUrl + '/justifikasi/deadline', {kode:kode}, function (data) {
            var data = $.parseJSON(data);
            // alert(data.dead_line);

            parent.find('.deadline').val(data.dead_line);
        });
    });
JS;
$this->registerJs($script);

if ($model->isNewRecord) {
    $tndk = Json::encode($tindakan->id);
    $js = <<<JS
        $(document).ready(function() {
            // alert($tndk);
            $("#justifikasidetail-0-id_kom_tindakan").val($tndk).trigger('change');
        });
    JS;
    $this->registerJs($js);
}
// $this->registerCssFile('@web/css/cahya.css');
?>

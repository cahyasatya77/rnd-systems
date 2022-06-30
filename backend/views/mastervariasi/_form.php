<?php

use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Mastervariasi */
/* @var $form yii\widgets\ActiveForm */

$list_dokumen = ArrayHelper::map($dokumen, 'id', 'kode');
?>

<div class="mastervariasi-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'dynamic-form',
            'class' => 'disable-submit-buttons',
        ],
    ]); ?>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Kategori Variasi</div>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'deskripsi')->textarea(['rows' => 3]) ?>
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
    ])?>

    <div class="card card-outline card-default">
        <div class="card-header card-title">
            <div class="row text-center">
                <div class="col-md-1">No.</div>
                <div class="col-md-10">Kode dan deskripsi</div>
                <div class="col-md-1">Action</div>
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
                            <div class="col-md-1 text-center">
                                <h6 class="number pt-2"><?= ($i+1)?></h6>
                            </div>
                            <div class="col-md-10">
                                <?= $form->field($detail, "[{$i}]id_master_dokumen")->widget(Select2::className(), [
                                    'data' => $list_dokumen,
                                    'options' => [
                                        'placeholder' => '- Pilih Kode Dokumen -'
                                    ],
                                ])->label(false)?>
                            </div>
                            <div class="col-md-1 text-center pt-1">
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

    <div class="card card-outline card-primary">
        <div class="card-body">
            <?= $form->field($model, 'status')->widget(Select2::className(), [
                'data' => [
                    'active' => 'active',
                    'in-active' => 'in-active',
                ],
            ])?>
        </div>
        <div class="card-footer">
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success float-right']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    jQuery('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        jQuery('.dynamicform_wrapper .number').each(function (index) {
            jQuery(this).html(index + 1);
        });
    });
    jQuery('.dynamicform_wrapper').on('afterDelete', function(e, item) {
        jQuery('.dynamicform_wrapper .number').each(function (index) {
            jQuery(this).html(index + 1);
        });
    });
JS;
$this->registerJs($script);
?>
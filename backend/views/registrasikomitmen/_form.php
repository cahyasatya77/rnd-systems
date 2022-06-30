<?php

// use Yii;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Registrasikomitmen */
/* @var $form yii\widgets\ActiveForm */

$list_komitmen = ArrayHelper::map($jenis_komitmen, 'id', 'jenis');
$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
?>

<div class="registrasikomitmen-form">
    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
    ]); ?>
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Create Komitmen</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'id_obat')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Produk -',
                            'id' => 'produk',
                        ],
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'bentuk_sediaan')->textInput([
                        'maxlength' => true, 
                        'readonly' => true,
                        'id' => 'bs',
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'keterangan')->textarea();?>
                </div>
            </div>
        </div>
    </div>

    <div class="divider-text">Komitmen</div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $model_jenis[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'id_jenis'
        ],
    ]);?>

    <div class="container-items">
        <?php foreach ($model_jenis as $i => $jenis) :?>
        <div class="item">
            <div class="row">
                <div class="col-md-1" style="display: table-cell; float:none; height:auto">
                    <h6 class="number" style="position: absolute; bottom: 2em; left: 1em;"><?= 'No : ' . ($i + 1) ?></h6>
                </div>
                <div class="col-md-5">
                    <?php
                        // necessary for update action.
                        if (!$jenis->isNewRecord) {
                            echo Html::activeHiddenInput($jenis, "[{$i}]id");
                        }
                    ?>

                    <?= $form->field($jenis, "[{$i}]id_jenis")->widget(Select2::className(), [
                        'data' => $list_komitmen,
                        'options' => [
                            'placeholder' => '- Pilih Jenis Komitmen -',
                        ],
                    ])?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($jenis, "[{$i}]deadline_komit")->widget(DatePicker::className(), [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ],
                    ])?>
                </div>
                <div class="col-md-1 text-center">
                    <label> Action </label>
                    <div class="text-center">
                        <button type="button" class="add-item btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                        <button type="button" class="remove-item btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                    </div>
                </div>
            </div>

            <?= $this->render('_form_detail', [
                'form' => $form,
                'i' => $i,
                'model_komitmen' => $model_komitmen[$i],
                'pic' => $pic,
            ]);?>

        <div class="divider-text">Komitmen</div>
        </div>
        <?php endforeach;?>
    </div>

    <?php DynamicFormWidget::end();?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- <div class="card-body">
                    < ?= $form->field($model, 'keterangan')->textarea(['rows' => 5]) ?>
                </div> -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
                        </div>
                        <div class="col-md-6 text-right">
                            <!-- <div class="form-group"> -->
                                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
    $('#produk').change(function() {
        var id = $(this).val();

        $.get('bentuksediaan', {id:id}, function(data) {
            var data = $.parseJSON(data);
            // alert(data['bentuk_sediaan']);
            $('#bs').attr('value', data['bentuk_sediaan']);
        });
    });

    $('#bentuk_sediaan').change(function(){
        var testId = $(this).val();
        
        $.get('get-data-parameter', {testId : testId}, function(data){
            var data = $.parseJSON(data);
            //alert(data);
            //$.post('_form.php', {variable: data});
        })
    });

    jQuery('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html('No : ' + (index + 1));
        });

        // jQuery('.dynamicform_wrapper .dynamicform_inner').on('afterInsert', function(e, item) {
        //     jQuery('.dynamicform_wrapper .dynamicform_inner .secondNumber').each(function (komit){
        //         jQuery(this).html((komit + 1))
        //     });
        // });
    });

    // $('.dynamicform_wrapper .dynamicform_inner .secondNumber').html($('.dynamicform_wrapper .number').val());

    jQuery('.dynamicform_wrapper').on('afterDelete', function(e) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html('No : ' + (index + 1))

            // jQuery('.dynamicform_wrapper .dynamicform_inner .secondNumber').each(function (komit){
            //     jQuery(this).html((komit + 1))
            // });
            // jQuery('.dynamicform_wrapper .dynamicform_inner').on('afterDelete', function(k, kom) {
                
            // });
        });
    });

JS;
$this->registerJs($script);
$this->registerCssFile(Yii::$app->request->baseUrl.'/css/cahya.css');
?>

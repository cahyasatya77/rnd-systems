<?php

use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $model_kategori common\models\Meetingkategori */
/* @var $variasi common\models\Mastervariasi */

$this->title = 'Create Evaluasi Reg';
$this->params['breadcrumbs'][] = ['label' => 'Meeting Reg', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'View', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$list_variasi = ArrayHelper::map($variasi, 'id', 'kode');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'nama_produk',
                                'ed_nie',
                                'submit_aero',
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => 'Status',
                                    'value' => $model->status->deskripsi,
                                ],
                                'tanggal_pembuatan',
                                'keterangan:ntext',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'dynamic-form',
                'class' => 'disable-submit-buttons',
            ],
        ])?>

        <?= $form->field($model, 'id')->textInput(['readonly' => true])->hiddenInput()->label(false);?>

        
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
            ])?>  

            <div class="card card-outline card-success">
                <div class="card-header">
                    <div class="row text-center">
                        <div class="col-md-1 card-title">No</div>
                        <div class="col-md-10 card-title">Evaluasi Registrasi</div>
                        <div class="col-md-1 card-title">Action</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-items">
                        <?php foreach ($model_kategori as $i => $kategori) :?>
                            <div class="item">
                                <?php
                                    if (!$kategori->isNewRecord) {
                                        echo Html::activeHiddenInput($kategori, "[{$i}]id");
                                    }
                                ?>
                                <div class="row">
                                    <div class="col-md-1 text-center pt-2">
                                        <h6 class="number"><?= ($i + 1)?></h6>
                                    </div>
                                    <div class="col-md-10">
                                        <?= $form->field($kategori, "[{$i}]id_variasi")->widget(Select2::className(), [
                                            'data' => $list_variasi,
                                            'options' => [
                                                'placeholder' => '- Pilih Evaluasi reg -'
                                            ],
                                            'pluginOptions' => [
                                                'allowclear' => true,
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

            <div class="card card-outline card-defaut">
                <div class="card-footer">
                    <?= Html::a('Back', ['view', 'id' => $model->id], ['class' => 'btn btn-danger']);?>
                    <?= Html::submitButton('Save', [
                        'class' => 'btn btn-success float-right',
                        'data' => ['disabled-text' => 'Validating ...']
                    ])?>
                </div>
            </div>

        <?php ActiveForm::end();?>
    </div>
</div>

<?php
$script = <<<JS
    jQuery('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html(index + 1);
        });
    });

    jQuery('.dynamicform_wrapper').on('afterDelete', function(e) {
        jQuery('.dynamicform_wrapper .number').each(function(index) {
            jQuery(this).html(index + 1);
        });
    });
JS;
$this->registerJs($script);
// $this->registerCssFile('@web/css/cahya.css');
?>
<?php

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $model_kategori common\models\Meetingkategori */
/* @var $model_dokumen common\models]Meetingdokumen */

use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'dynamic-form',
        'class' => 'disable-submit-buttons',
    ],
])?>

<div class="divider-text">Evaluasi REG</div>
<div class="row">
    <div class="col-md-12">
        
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
                'deskripsi',
            ],
        ]);?>

        <div class="container-items">
            <?php foreach ($model_kategori as $i => $kategori) : ?>
                <div class="item">
                    <?php 
                        //necessary for update action.
                        if (!$kategori->isNewRecord) {
                            echo Html::activeHiddenInput($kategori, "[{$i}]id");
                        }
                    ?>

                    <div class="row">
                        <div class="col-md-1" style="display: table-cell; float: none; height:auto;">
                            <h6 class="number" style="position: absolute; bottom: 2em; left: 1em;"><?= 'No : '.($i+1)?></h6>
                        </div>
                        <div class="col-md-10">
                            <?= $form->field($kategori, "[{$i}]deskripsi")->textarea(['rows' => 2])->label('Evaluasi');?>
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

        <?php DynamicFormWidget::end()?>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-default">
            <div class="card-footer">
                <?= Html::a('Back', ['view', 'id' => $model->id], ['class' => 'btn btn-danger'])?>
                <?= Html::submitButton('Save', [
                    'class' => 'btn btn-success float-right',
                    'data' => ['disable-text' => 'Validating....']
                ])?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end();?>


<?php 
$script = <<<JS
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
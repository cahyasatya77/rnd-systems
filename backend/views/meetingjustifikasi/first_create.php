<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model common\models\ModelStatis */

$this->title = 'Justifikasi';
$this->params['breadcrumbs'][] = ['label' => 'index', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'create'];

$list_produk = ArrayHelper::map($produk, 'id', 'item');
?>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin();?>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Create</div>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'id_meeting')->widget(Select2::className(), [
                    'data' => $list_produk,
                    'options' => [
                        'placeholder' => '- Pilih Produk -',
                        'id' => 'produk',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])?>

                <?= $form->field($model, 'variasi')->widget(Select2::className(), [
                    'data' => null,
                    'options' => [
                        'placeholder' => '- Pilih Variasi -',
                        'id' => 'variasi',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])?>

                <?= $form->field($model, 'tindakan')->widget(Select2::className(), [
                    'data' => null,
                    'options' => [
                        'placeholder' => '- Pilih Dokumen -',
                        'id' => 'dokumen',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Dokumen');?>
            </div>
            <div class="card-footer">
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success float-right'])?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>

<?php
$urlBase = Json::encode(Yii::$app->urlManager->baseUrl.'/meetingjustifikasi');
$script = <<<JS
    $('#produk').change(function() {
        var id = $(this).val();

        $.get($urlBase + '/variasi', {id:id}, function(data) {
            // alert(id);
            $('select#variasi').html(data);
            $('select#variasi').val('').trigger('change');
        });
    });

    $('#variasi').change(function() {
        var id = $(this).val();

        $.get($urlBase + '/dokumen', {id:id}, function(data) {
            // alert(id);
            $('select#dokumen').html(data);
            $('select#dokumen').val('').trigger('change');
        });
    });
JS;
$this->registerJs($script);
?>
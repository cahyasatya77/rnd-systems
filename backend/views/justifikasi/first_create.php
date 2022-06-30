<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Modelstatis */

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
                <?= $form->field($model, 'id_komitmen')->widget(Select2::className(), [
                    'data' => $list_produk,
                    'options' => [
                        'placeholder' => '- Pilih Produk -',
                        'id' => 'produk',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])?>

                <?= $form->field($model, 'komitmen')->widget(Select2::className(), [
                    'data'=> null,
                    'options' => [
                        'placeholder' => '- Pilih Komitmen -',
                        'id' => 'komitmen',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>

                <?= $form->field($model, 'tindakan')->widget(Select2::className(), [
                    'data' => null,
                    'options' => [
                        'placeholder' => '- Pilih Tindakan -',
                        'id' => 'tindakan',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])?>
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
$script = "
    $('#produk').change(function() {
        var id = $(this).val();

        $.get('".Yii::$app->urlManager->baseUrl."/justifikasi/komitmen', {id:id}, function(data){
            // alert(data);
            $('select#komitmen').html(data);
            $('select#komitmen').val('').trigger('change');
        });
    });
    $('#komitmen').change(function() {
        var id = $(this).val();
        // alert(id);
        $.get('".Yii::$app->urlManager->baseUrl."/justifikasi/tindakan', {id:id}, function(data) {
            // alert(data);
            $('select#tindakan').html(data);
            $('select#tindakan').val('').trigger('change');
        });
    });
";
$this->registerJs($script);
?>
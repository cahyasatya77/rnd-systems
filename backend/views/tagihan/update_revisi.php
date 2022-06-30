<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Regkomtindakan */
/* @var $form yii\bootstrap4\ActiveForm */

$list_pic = ArrayHelper::map($user_pic, 'id', 'username');

$this->title = 'Revisi Tindakan Komitmen';
$this->params['breadcrumbs'][] = ['label' => 'index revisi', 'url' => ['indexrevisipic']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tindakan-form">

    <?php if ($model->status_pic == 'revisi') :?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Catatan Revisi !</h5>
            <?= $model->keterangan_revisi; ?>
        </div>
    <?php endif;?>
    <?php if (($model->status_pic == 'revisi') and ($model->revisi_rnd != null)) :?>
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Catatan Revisi dari R&D !</h5>
            <?= $model->revisi_rnd; ?>
        </div>
    <?php endif;?>
    <!-- Detail Information -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <div class="card-title">Detail Komitmen</div>
            <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
        </div>
        <div class="card-body p-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Nama Obat',
                        'value' => $model->jnsKomitmen->komitmen->nama_obat,
                    ],
                    [
                        'label' => 'Komitmen',
                        'value' => $model->jnsKomitmen->jenis->jenis,
                    ],
                    [
                        'attribute' => 'dokumen',
                    ],
                    [
                        'attribute' => 'pic',
                        'value' => $model->user->username,
                    ],
                    'status_pic'
                ],
            ])?>
        </div>
    </div>
        
    <!-- Upload Lampiran -->

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'disable-submit-buttons',
        ],
    ]);?>

    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Upload Komitmen</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($model->nama_dok == null) :?>
                        <?= $form->field($model, 'nama_dok')->fileInput()->hint('Maximum size file 2 Mb and only Pdf extension')->label('Lampiran'); ?>
                    <?php else :?>
                        <label>Lampiran komitmen : </label>
                        <br>
                        <?= Html::a($model->nama_dok, ['download', 'id' => $model->id]);?>
                        <?= Html::a('delete', ['deletedok', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs']);?>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'manager_pic')->widget(Select2::className(), [
                        'data' => $list_pic,
                        // 'options' => [
                        //     'placeholder' => '- Pilih Manager -',
                        // ],
                        // 'pluginOptions' => [
                        //     'allowClear' => true,
                        // ],
                    ])->label('Send to')?>

                    <?= $form->field($model, 'keterangan')->textarea();?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Back', ['indexrevisipic'], ['class' => 'btn btn-danger'])?>
                    <?= Html::submitButton('Submit', [
                        'class' => 'btn btn-success float-right',
                        'data' => ['disabled-text' => 'Validating ...'],
                    ])?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>

<?php
    $this->registerCssFile('@web/css/cahya.css');
?>
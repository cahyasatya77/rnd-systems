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
?>

<div class="tindakan-form">

    <!-- Detail Information -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <div class="card-title">Detail Komitmen</div>
        </div>
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-6">
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
                                'attribute' => 'pic',
                                'value' => $model->user->username,
                            ],
                            [
                                'label' => 'Keterangan',
                                'value' => $model->jnsKomitmen->komitmen->keterangan,
                            ],
                        ],
                    ])?>
                </div>
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'status_pic',
                            [
                                'label' => 'Deadline Komitmen',
                                'value' => $model->jnsKomitmen->deadline_komit,
                            ],
                            [
                                'label' => 'Deadline Tindakan',
                                'attribute' => 'dead_line',
                            ],
                            [
                                'attribute' => 'dokumen',
                            ],
                        ],
                    ])?>
                </div>
            </div>
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
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
                    <?= Html::submitButton('Submit', [
                        'class' => 'btn btn-success float-right',
                        'data' => ['disabled-text' => 'Validating ...']
                    ])?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>

<?php
    // $this->registerCssFile('@web/css/cahya.css');
?>
<?php

use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $model_kategori common\models\Meetingkategori */
/* @var $model_dokumen common\models\Meetingdokumen */

$this->title = $model->id_transaksi;
$this->params['breadcrumbs'][] = ['label' => 'Meeting Registrasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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
                                'tanggal_pembuatan',
                                [
                                    'label' => 'Status',
                                    'value' => $model->status->deskripsi,
                                ],
                                'keterangan:ntext',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'dynamic-form',
                'class' => 'disable-submit-buttons'
            ],
        ]);?>
            <?php echo Html::activeHiddenInput($model, 'id');?>

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
                'id',
            ],
        ]);?>

        <div class="container-items">
            <?php foreach ($model_kategori as $i => $kategori) :?>
                <div class="item">
                    <?php
                        // necessary for update action.
                        if (!$kategori->isNewRecord) {
                            echo Html::activeHiddenInput($kategori, "[{$i}]id");
                        }
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-1"><?= ($i+1) ?></div>
                                        <div class="col-md-1"><?= $kategori->kode?></div>
                                        <div class="col-md-10"><?= $kategori->deskripsi?></div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <?= $this->render('_form_detail_update', [
                                        'form' => $form,
                                        'i' => $i,
                                        'model_dokumen' => $model_dokumen[$i],
                                        'pic' => $pic,
                                        'variasi_dok' => $variasi_dok,
                                    ])?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>

        <?php DynamicFormWidget::end();?>

        <div class="card">
            <div class="card-footer">
                <?= Html::submitButton('Save', [
                    'class' => 'btn btn-success float-right',
                    'data' => ['disabled-text' => 'Validating ...']
                ])?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>
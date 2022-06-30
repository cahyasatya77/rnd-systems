<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingdokumen */

$this->title = 'Revisi Dokumen';
$this->params['breadcrumbs'][] = ['label' => 'List Approve', 'url' => ['indexapprove']];
$this->params['breadcrumbs'][] = ['label' => 'Approve', 'url' => ['approve', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="meetingdokumen-form-revisi">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <div class="card-title">Revisi dokumen</div>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'label' => 'Nama Produk',
                                        'value' => $model->kategori->meeting->nama_produk,
                                    ],
                                    [
                                        'label' => $model->kategori->jenisMeeting->deskripsi,
                                        'value' => $model->kategori->deskripsi,
                                    ],
                                ],
                            ])?>
                        </div>
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'deadline',
                                    [
                                        'label' => 'PIC submit dokumen',
                                        'attribute' => 'tanggal_submit',
                                    ],
                                ],
                            ]);?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'label' => 'Dokumen permintaan',
                                        'attribute' => 'dokumen'
                                    ],
                                ],
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'disable-submit-buttons',
        ],
    ]);?>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-danger">
                <div class="card-body">
                    <?= $form->field($model, 'revisi_mng_pic')->textarea(['rows' => 4])->label('Revisi')?>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::a('Back', ['approve', 'id' => $model->id], ['class' => 'btn btn-danger'])?>
                            <?= Html::submitButton('Save', [
                                'class' => 'btn btn-success float-right',
                                'data' => ['disabled-text' => 'Validating ...'],
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end();?>
</div>
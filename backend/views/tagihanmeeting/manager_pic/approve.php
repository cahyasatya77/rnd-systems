<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingdokumen */

$this->title = 'Approve Tindakan';
$this->params['breadcrumbs'][] = ['label' => 'List Approve', 'url' => ['indexapprove']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="meetingdokumen-form-approve">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <div class="card-title">Detail Dokumen</div>
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
                                        'label' => 'Submit new AERO',
                                        'value' => function ($data) {
                                            return '<div class="p-2 bg-warning">'.date('d F Y', strtotime($data->kategori->meeting->submit_aero)).'</div>';
                                        },
                                        'format' => 'raw',
                                    ],
                                    [
                                        'label' => 'Dokumen permintaan',
                                        'attribute' => 'dokumen'
                                    ],
                                    [
                                        'label' => 'Catatan dari R&D',
                                        'attribute' => 'note_rnd'
                                    ],
                                    [
                                        'label' => 'Lampiran Dokumen',
                                        'value' => function ($data) {
                                            if ($data->nama_dok == null) {
                                                return '<strong class="text-danger">Lampiran dokumen belum diupload</strong>';
                                            } else {
                                                return  Html::a($data->nama_dok, ['download', 'id' => $data->id]);
                                            }
                                        },
                                        'format' => 'raw'
                                    ],
                                    'keterangan',
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
        <div class="col-md-6">
            <?= Html::a('Revisi', ['revisi', 'id' => $model->id], ['class' => 'btn btn-danger btn-block']);?>
        </div>
        <div class="col-md-6">
            <?= Html::activeHiddenInput($model, 'id');?>
            <?= Html::submitButton('Approve', [
                'class' => 'btn btn-success btn-block',
                'data' => ['disabled-text' => 'Validating ...']
            ])?>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>

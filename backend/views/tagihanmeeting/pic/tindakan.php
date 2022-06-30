<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var common\models\Meetingdokumen */
/* @var $form yii\bootstrap4\Activeform */

$list_pic = ArrayHelper::map($user_pic, 'id', 'username');

$this->title = 'Upload';
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tindakan-form">
    <!-- Detail Information -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <div class="card-title">Detail Notulen Meeting Registrasi : <?= $model->kategori->meeting->id_transaksi?></div>
        </div>
        <div class="box-body p-0">
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
                                'label' => 'Keterangan dari registrasi',
                                'value' => $model->kategori->meeting->keterangan,
                            ],
                        ] 
                    ]);?>
                </div>
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'label' => 'PIC',
                                'value' => $model->user->username,
                            ],
                            [
                                'label' => 'Deadline',
                                'attribute' => 'deadline'
                            ],
                        ] 
                    ]);?>
                </div>
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
                            'label' => $model->kategori->jenisMeeting->deskripsi,
                            'value' => $model->kategori->deskripsi,
                        ],
                        'dokumen',
                        'note_rnd',
                    ] 
                ]);?>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'disable-submit-buttons',
        ],
    ])?>

    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-title">Upload Dokumen</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($model->nama_dok == null) :?>
                        <?= $form->field($model, 'nama_dok')->fileInput()->hint('Maximum size file 2 Mb and only Pdf extension')->label('Lampiran')?>
                    <?php else :?>
                        <label>Lampiran dokumen : </label>
                        <br>
                        <?= Html::a($model->nama_dok, ['download', 'id' => $model->id]);?>
                        <?= Html::a('delete', ['deletedok', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs']);?>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'pic_manager')->widget(Select2::className(), [
                        'data' => $list_pic,
                    ])->label('Send to')?>

                    <?= $form->field($model, 'keterangan')->textarea()?>
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

<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Regkomtindakan */

$this->title = 'Review';
$this->params['breadcrumbs'][] = ['label' => 'index review', 'url' => ['indexreview']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="regkomitindakan-review">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <div class="card-title">Detail review komitmen</div>
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
                                        'label' => 'Deadline Komitmen',
                                        'value' => date('d F Y', strtotime($model->jnsKomitmen->deadline_komit)),
                                    ],
                                    [
                                        'label' => 'Keterangan R&D Department',
                                        'value' => $model->jnsKomitmen->komitmen->keterangan,
                                    ],
                                    [
                                        'label' => 'PIC',
                                        'value' => $model->user->username,
                                    ],
                                ],
                            ])?>
                        </div>
                        <div class="col-md-6">
                             <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [ 
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
                                    'dokumen',
                                    [
                                        'label' => 'Keterangan PIC',
                                        'attribute' => 'keterangan',
                                    ],
                                    [
                                        'label' => 'Tanggal Submit Dokumen',
                                        'value' => date('d F Y', strtotime($model->tanggal_submit)),
                                    ],
                                    [
                                        'label' => 'Deadline Tagihan',
                                        'value' => date('d F Y', strtotime($model->dead_line)),
                                    ],
                                ],
                            ])?>
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
    ])?>
    <div class="row">
        <div class="col-md-6">
            <?= Html::a('Revisi', ['revisirnd', 'id' => $model->id], ['class' => 'btn btn-danger btn-block'])?>
        </div>
        <div class="col-md-6">
            <?= Html::activeHiddenInput($model, 'id');?>
            <?= Html::submitButton('Approve', [
                'class' => 'btn btn-success btn-block'
            ])?>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>

<?php // $this->registerCssFile('@web/css/cahya.css');?>
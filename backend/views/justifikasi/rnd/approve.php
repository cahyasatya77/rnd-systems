<?php

use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Justifikasi */

$this->title = 'Approve';
$this->params['breadcrumbs'][] = ['label' => 'Justifikasi', 'url' => ['indexreview']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Approve Justifikasi</div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model->registrasikomitmen,
                            'options' => [
                                'class' => 'table detail-view',
                            ],
                            'attributes' => [
                                'id_transaksi',
                                'nama_obat',
                                'bentuk_sediaan',
                            ], 
                        ]);?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model->jenisKomitmen,
                            'options' => [
                                'class' => 'table detail-view',
                            ],
                            'attributes' => [
                                [
                                    'label' => 'Komitmen',
                                    'value' => $model->jenisKomitmen->jenis->jenis,
                                ],
                                [
                                    'label' => 'Deadline Komitmen',
                                    'value' => function ($data) {
                                        return '<div class="p-2 bg-warning">'.date('d F Y', strtotime($data->deadline_komit)).'</div>';
                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => [
                                'class' => 'table detail-view',
                            ],
                            'attributes' => [
                                'alasan_justif:ntext',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-title">Dokumen Komitmen</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '{items}{pager}',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'header' => 'Dokumen',
                            'value' => function ($model) {
                                return $model->komitmenTindakan->dokumen;
                            } 
                        ],
                        [
                            'header' => 'Deadline Awal',
                            'attribute' => 'deadline_old'
                        ],
                        [
                            'header' => 'Deadline Baru',
                            'attribute' => 'deadline_new',
                        ],
                    ],
                ])?>
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
        <?= Html::a('Revisi', ['revisirnd', 'id' => $model->id], ['class' => 'btn btn-danger btn-block']);?>
    </div>
    <div class="col-md-6">
        <?= Html::activeHiddenInput($model, 'id');?>
        <?= Html::submitButton('Approve', [
            'class' => 'btn btn-success btn-block',
            'data' => ['disabled-text' => 'Validating ...']
        ])?>
    </div>
</div>
<br>

<?php ActiveForm::end();?>

<?php $this->registerCssFile('@web/css/cahya.css');?>
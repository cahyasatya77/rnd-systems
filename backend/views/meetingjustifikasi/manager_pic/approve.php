<?php

use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingjustifikasi */

$this->title = 'Approve';
$this->params['breadcrumbs'][] = ['label' => 'Justifikasi', 'url' => ['indexapprovepic']];
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
                            'model' => $meeting,
                            'options' => [
                                'class' => 'table detail-view',
                            ],
                            'attributes' => [
                                'id_transaksi',
                                'nama_produk',
                                'keterangan',
                            ],
                        ])?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $meeting,
                            'options' => [
                                'class' => 'table detail-view'
                            ],
                            'attributes' => [
                                'ed_nie',
                                'submit_aero',
                                [
                                    'label' => $kategori->jenisMeeting->deskripsi,
                                    'value' => $kategori->deskripsi,
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
                                'class' => 'table detail-view'
                            ],
                            'attributes' => [
                                'alasan_justif',
                                // [
                                //     'label' => 'PIC Manager',
                                //     'value' => function ($data) {
                                //         $manager_pic = User::findOne($data->pic_manager);
                                //         return $manager_pic->username;
                                //     }
                                // ],
                            ],
                        ])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-default">
            <div class="card-header">
                <div class="card-title">Detail Justifikasi</div>
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
                                return $model->dokumen->dokumen;
                            }
                        ],
                        [
                            'header' => 'Deadline Awal',
                            'attribute' => 'deadline_old',
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
        'class' => 'disable-submit-buttons'
    ],
])?>

<div class="row">
    <div class="col-md-6">
        <?= Html::a('Revisi', ['revisipic', 'id' => $model->id], ['class' => 'btn btn-danger btn-block']);?>
    </div>
    <div class="col-md-6">
        <?= Html::activeHiddenInput($model, 'id');?>
        <?= Html::submitButton('Approve', [
            'class' => 'btn btn-success btn-block',
            'data' => ['disable-text' => 'Validating ...'],
        ])?>
    </div>
</div>

<?php ActiveForm::end();?>

<br>

<?php
    $this->registerCssFile('@web/css/cahya.css');
?>
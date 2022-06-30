<?php

use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $model common\models\Regjnskomitmen */

$this->title = 'View';
$this->params['breadcrumbs'][] = ['label' => 'BPOM', 'url' => ['registrasikomitmen/indexbpom']];
$this->params['breadcrumbs'][] = ['label' => 'history', 'url' => ['history']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Komitmen</div>
                <div class="float-right"><?= Html::a('Back', ['history'], ['class' => 'btn btn-outline-danger btn-xs'])?></div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => 'Nama Obat',
                                    'value' => $model->komitmen->nama_obat,
                                ],
                                [
                                    'label' => 'Bentuk Sediaan',
                                    'value' => $model->komitmen->bentuk_sediaan,
                                ],
                                [
                                    'label' => 'Komitmen',
                                    'value' => $model->jenis->jenis,
                                ],
                                [
                                    'label' => 'Deadline Komitmen',
                                    'attribute' => 'deadline_komit'
                                ],
                            ],
                        ]);?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'tanggal_close',
                                [
                                    'label' => 'Surat Pengantar',
                                    'value' => function ($model) {
                                        if ($model->surat_pengantar == null) {
                                            return '<strong class="text-danger">Surat Pengantar belum diupload</strong>';
                                        } else {
                                            return Html::a($model->surat_pengantar, ['download', 'id' => $model->id]);
                                        }
                                    },
                                    'format' => 'raw'
                                ],
                                [
                                    'label' => 'Keterangan',
                                    'value' => $model->komitmen->keterangan,
                                ],
                                [
                                    'label' => 'Keterangan BPOM',
                                    'attribute' => 'ket_bpom',
                                ],
                            ],
                        ]);?>
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
                <div class="card-title">Detail Komitmen</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataDetail,
                    'tableOptions' => [
                        'class' => 'table table-striped',
                    ],
                    'layout' => '{items}{pager}',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'dokumen',
                        [
                            'attribute' => 'pic',
                            'value' => function ($data) {
                                return $data->user->username;
                            }
                        ],
                        'dead_line',
                        [
                            'header' => 'Lampiran',
                            'value' => function ($data) {
                                return $data->nama_dok;
                            }
                        ],
                        'tanggal_submit',
                        'keterangan',
                    ],
                ]);?>
            </div>
        </div>
    </div>
</div>
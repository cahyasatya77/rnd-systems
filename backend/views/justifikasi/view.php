<?php

use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Justifikasi */

$this->title = 'view';
$this->params['breadcrumbs'][] = ['label' => 'Justifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="justifikasi-view">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title">Detail Komitmen</div>
                    <?= Html::a('Index', ['index'], ['class' => 'btn btn-outline-primary float-right'])?>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $komitmen,
                                'options' => [
                                    'class' => 'table detail-view',
                                ],
                                'attributes' => [
                                    'id_transaksi',
                                    'nama_obat',
                                    'bentuk_sediaan',
                                ],
                            ])?>
                        </div>
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $jenis_komitmen,
                                'options' => [
                                    'class' => 'table detail-view',
                                ],
                                'attributes' => [
                                    [
                                        'label' => 'Komitmen',
                                        'value' => $jenis_komitmen->jenis->jenis,
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
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title">Justifikasi</div>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => [
                            'class' => 'table detail-view',
                        ],
                        'attributes' => [
                            'alasan_justif:ntext',
                            [
                                'label' => 'Status',
                                'value' => function ($model){
                                    return $model->options->deskripsi;
                                }
                            ],
                            [
                                'label' => 'PIC Manager',
                                'value' => function ($model) {
                                    $user = User::findOne($model->pic_manager);
                                    return $user->username;
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title">Komitmen Terdampak</div>
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

</div>

<?php $this->registerCssFile('@web/css/cahya.css');?>
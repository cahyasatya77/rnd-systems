<?php

use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingjustifikasi */

$this->title = 'View';
$this->params['breadcrumbs'][] = ['label' => 'Justifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="meetingjustifikasi-view">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title">Detail Notulen</div>
                    <?= Html::a('Index', ['index'], ['class' => 'btn btn-outline-primary float-right'])?>
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
                                    [
                                        'label' => 'PIC Manager',
                                        'value' => function ($data) {
                                            $manager_pic = User::findOne($data->pic_manager);
                                            return $manager_pic->username;
                                        }
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

</div>

<?php
    $this->registerCssFile('@web/css/cahya.css');
?>

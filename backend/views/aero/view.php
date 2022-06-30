<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var model common\models\Meetingregistrasi */
/* @var $searchModel backend\models\MeetingregistrasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->id_transaksi;
$this->params['breadcrumbs'][] = ['label' => 'List Aero', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'List History', 'url' => ['history']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
                <?= Html::a('Back', ['history'], ['class' => 'btn btn-outline-danger btn-sm float-right'])?>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id_transaksi',
                                'nama_produk',
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'ed_nie',
                                'submit_aero',
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'keterangan:ntext',
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
        <div class="card card-outline card-default">
            <div class="card-header">
                <div class="card-title">Detail Meeting Registrasi</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'striped' => false,
                    'layout' => '{items}{pager}',
                    'columns' => [
                        // ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'attribute' => 'id_kategori',
                            'value' => function ($data) {
                                return $data->kategori->jenisMeeting->deskripsi;
                            },
                            'group' => true,
                            'groupedRow' => true,
                            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                        ],
                        'dokumen', 
                        [
                            'attribute' => 'pic',
                            'value' => 'user.username',
                        ],
                        [
                            'header' => 'Lampiran',
                            'value' => function ($data) {
                                if ($data->nama_dok != null) {
                                    return Html::a($data->nama_dok, ['download', 'id' => $data->id]);
                                } else {
                                    return '<strong class="text-red">not found</strong>';
                                }
                            },
                            'format' => 'raw',
                        ],
                        // 'status',
                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                if ($model->status == 'done') {
                                    return '<i class="fas fa-check" style="color:green;"></i>';
                                } else {
                                    return $model->status;
                                }
                            },
                            'format' => 'raw',
                        ],
                        'keterangan',
                    ],
                ])?>
            </div>
        </div>
    </div>
</div>
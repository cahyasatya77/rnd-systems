<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */

$this->title = $model->id_transaksi;
$this->params['breadcrumbs'][] = ['label' => 'List Revisi', 'url' => ['indexrevisi']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
                <?= Html::a('Back', ['indexrevisi'], ['class' => 'btn btn-outline-danger btn-sm float-right'])?>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id_transaksi',
                                'nama_produk',
                                'ed_nie',
                                'submit_aero',
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'rnd_manager',
                                'approve_manager',
                                'tanggal_pembuatan',
                                'tanggal_close',
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => 'Status',
                                    'value' => $model->status->deskripsi,
                                ],
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
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Kategori Variasi</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataVariasi,
                    'layout' => '{items}{pager}',
                    'columns' => [
                        // ['class' => 'yii\grid\SearialColumn'],
                        [
                            'header' => 'Variasi',
                            'value' => 'kategori.deskripsi',
                            'group' => true,
                        ],
                        [
                            'header' => 'Dokumen Mutu yang dibutuhkan',
                            'attribute' => 'dokumen'
                        ],
                        'note_rnd',
                        'status',
                        [
                            'header' => 'PIC',
                            'attribute' => 'pic',
                            'value' => 'user.username',
                        ],
                        [
                            'header' => 'Deadline',
                            'value' => function ($data) {
                                return date('d F Y', strtotime($data->deadline));
                            }
                        ],
                    ],
                ]);?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($dataEvaluasi->getModels())) :?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title">Evaluasi Reg</div>
                </div>
                <div class="card-body p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataEvaluasi,
                        'layout' => '{items}{pager}',
                        'columns' => [
                            [
                                'header' => 'Evaluasi', 
                                'value' => 'kategori.deskripsi',
                                'group' => true,
                            ],
                            [
                                'header' => 'Dokumen Mutu',
                                'attribute' => 'dokumen',
                            ],
                            'note_rnd',
                            'status',
                            [
                                'header' => 'PIC',
                                'attribute' => 'pic',
                                'value' => 'user.username',
                            ],
                            [
                                'header' => 'Deadline',
                                'value' => function ($data) {
                                    return date('d F Y', strtotime($data->deadline));
                                }
                            ],
                        ],
                    ]);?>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>

<?php $this->registerCss("
    table.detail-view th {
        width: 40%;
        padding: 0.6rem;
    }

    table.detail-view td {
        width: 60%;
        padding: 0.6rem;
    }
");?>

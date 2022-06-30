<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Metingregistasi */


$this->title = 'Approve';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['indexmanager']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Approve Notulen Meeting Registasi</div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id_transaksi',
                                'nama_produk',
                                'submit_aero',
                            ],
                        ])?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'ed_nie',
                                'tanggal_pembuatan',
                                [
                                    'label' => 'Status',
                                    'value' => $model->status->deskripsi,
                                ],
                            ], 
                        ])?>
                    </div>
                    <div class="col-md-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'keterangan:ntext',
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
                <div class="card-title">Kategori Variasi</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataVariasi,
                    'layout' => '{items}{pager}',
                    'columns' => [
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
                ])?>
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

<?php $form = ActiveForm::begin([
    'options' => ['class' => 'disable-submit-buttons'],
])?>
<div class="row">
    <div class="col-md-6">
        <?= Html::a('Revisi', ['revisi', 'id' => $model->id], ['class' => 'btn btn-danger btn-block']);?>
    </div>
    <div class="col-md-6">
        <?= Html::activeHiddenInput($model, 'id');?>
        <?= Html::submitButton('Approve', [
            'class' => 'btn btn-success btn-block',
            'data' => ['disabled-text' => 'Validating....']
        ])?>
    </div>
</div>
<br>
<?php ActiveForm::end();?>

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

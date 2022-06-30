<?php

use kartik\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $searchModel backend\models\MeetingregistrasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->id_transaksi;
$this->params['breadcrumbs'][] = ['label' => 'List New Aero', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-outline-danger btn-sm float-right'])?>
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
                        'nama_dok',
                        'status',
                        'keterangan',
                    ],
                ])?>
            </div>
        </div>
    </div>
</div>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        'class' => 'disable-submit-buttons',
    ],
])?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Input</div>
            </div>
            <div class="card-body">
                <?php if ($model->surat_pengantar == null) :?>
                    <?= $form->field($model, 'surat_pengantar')->fileInput()
                            ->hint('Maximum size file 2 Mb and only Pdf extension')
                            ->label('Lampiran Surat Pengantar')?>
                <?php else :?>
                    <label>Lampiran Surat Pengantar :</label>
                    <br>
                    <?= Html::a($model->surat_pengantar, ['downloadsurat', 'id' => $model->id]);?>
                    <?= Html::a('delete', ['deletesurat', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs']);?>
                <?php endif;?>

                <?= $form->field($model, 'note_close')->textarea();?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <?= Html::a('Back', ['indexbpom'], ['class' => 'btn btn-danger'])?>
                        <?= Html::submitButton('Close', [
                            'class' => 'btn btn-success float-right',
                            'data' => ['disabled-text' => 'Validating ...']
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end();?>

<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegkomtindakanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Close komitmen';
$this->params['breadcrumbs'][] = ['label' => 'index', 'url' => ['indexbpom']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Komitmen</div>
            </div>
            <div class="card-body p-0">
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
                    ],
                ]);?>
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

                <?= $form->field($model, 'ket_bpom')->textarea();?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <?= Html::a('Back', ['history'], ['class' => 'btn btn-danger'])?>
                        <?= Html::submitButton('Done', [
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


<?php $this->registerCssFile('@web/css/cahya.css');?>
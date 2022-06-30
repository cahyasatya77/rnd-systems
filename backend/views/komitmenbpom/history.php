<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegistrasikomitmenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'History';
$this->params['breadcrumbs'][] = ['label' => 'BPOM', 'url' => ['registrasikomitmen/indexbpom']];
$this->params['breadcrumbs'][] = $this->title;

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
$list_komitmen = ArrayHelper::map($komitmen, 'id', 'jenis');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-default">
            <?php $form = ActiveForm::begin([
                'action' => ['history'],
                'method' => 'get',
            ])?>

            <div class="card-header">
                <div class="card-title">Search</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($searchModel, 'id_obat')->widget(Select2::className(), [
                            'data' => $list_produk,
                            'options' => [
                                'placeholder' => '- Pilih Produk -'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false)?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($searchModel, 'komitmen')->widget(Select2::className(), [
                            'data' => $list_komitmen,
                            'options' => [
                                'placeholder' => '- Pilih Komitmen -',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false)?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($searchModel, 'date')->widget(DatePicker::className(), [
                            'options' => [
                                'placeholder' => '- Pilih Bulan Submit -'
                            ],
                            'pluginOptions' => [
                                'minViewMode' => 'months',
                                'autoclose' => true,
                                'format' => 'yyyy-mm',
                            ],
                        ])->label(false)?>
                    </div>
                    <div class="col-md-1">
                        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-success btn-block'])?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end();?>
        </div>
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <div class="card-title">Submit to BPOM</div>
    </div>
    <div class="card-body p-0">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'layout' => '{items}{pager}',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'header' => 'ID Komitmen',
                    'value' => function($model) {
                        return $model->komitmen->id_transaksi;
                    }
                ],
                [
                    'header' => 'Nama Obat',
                    'value' => function ($model) {
                        return $model->komitmen->nama_obat;
                    }
                ],
                [
                    'header' => 'Komitmen',
                    'value' => function ($model) {
                        return $model->jenis->jenis;
                    }
                ],
                'deadline_komit',
                [
                    'header' => 'Status',
                    'attribute' => 'status'
                ],

                [
                    'header' => 'View',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                        }
                    ],
                ],
                [
                    'header' => 'Update',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('update', $url, ['class' => 'btn btn-warning btn-xs']);
                        }
                    ],
                ]
            ],
        ]);?>
    </div>
</div>
<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TagihanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tagihan PIC';
$this->params['breadcrumbs'][] = $this->title;

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
?>

<div class="registrasikomitmen-search">
    
    <div class="card card-outline card-default">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="card-header">
            <div class="card-title">Search</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($searchModel, 'nama_obat')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Produk -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(false) ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($searchModel, 'dead_line')
                        ->widget(DatePicker::className(), [
                            'options' => [
                                'placeholder' => '- Pilih tanggal Deadline -'
                            ],
                            'pluginOptions' => [
                                'minViewMode'=>'months',
                                'autoclose' => true,
                                'format' => 'yyyy-mm'
                            ],
                        ])
                        ->label(false) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

<div class="tagihan-index">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Tagihan</div>
        </div>
        <div class="card-body table-responsive p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'layout' => '{items}{pager}',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'header' => 'Nama Obat',
                        'value' => function ($data) {
                            return $data->jnsKomitmen->komitmen->nama_obat;
                        }
                    ],
                    [
                        'header' => 'Komitmen',
                        'attribute' => 'id_jns_komitmen',
                        'value' => function ($data) {
                            return $data->jnsKomitmen->jenis->jenis;
                        }
                    ],
                    'dokumen',
                    'dead_line',
                    // [
                    //     'attribute' => 'pic',
                    //     'value' => function ($data) {
                    //         return $data->user->username;
                    //     }
                    // ],
                    'status_pic',

                    [
                        'header' => 'Tindakan',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{tindakan}',
                        'buttons' => [
                            'tindakan' => function ($url, $model) {
                                $url = Url::to(['tindakan', 'id' => $model->id]);
                                return Html::a('upload', $url, ['class' => 'btn btn-info btn-xs']);
                            }
                        ],
                        'visibleButtons' => [
                            'tindakan' => function ($model) {
                                if ($model->status_pic == 'review') {
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        ],
                    ],
                    [
                        'header' => 'Kirim',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{kirim}',
                        'buttons' => [
                            'kirim' => function ($url, $model) {
                                $url = Url::to(['kirim', 'id' => $model->id]);
                                return Html::a('kirim', $url, [
                                    'class' => 'btn btn-success btn-xs',
                                    'data' => [
                                        'confirm' => 'Are you sure want to send this item to your manager ?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'visibleButtons' => [
                            'kirim' => function ($model) {
                                if (($model->status_pic == 'open') || ($model->status_pic == 'review')) {
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        ],
                    ],
                    [
                        'header' => 'Justifikasi',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{justif}',
                        'buttons' => [
                            'justif' => function ($url, $model) {
                                $url = Url::to(['justifikasi/create', 'id' => $model->jnsKomitmen->komitmen->id, 'kom' => $model->jnsKomitmen->id, 'tnd' => $model->id]);
                                return Html::a('justifikasi', $url, ['class' => 'btn btn-warning btn-xs']);
                            }
                        ],
                    ],
                ],
            ])?>
        </div>
    </div>
</div>
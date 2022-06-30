<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TagihanmeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $produk yii\db\Query */

$this->title = 'Tagihan PIC';
$this->params['breadcrumbs'][] = $this->title;

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
?>

<div class="tagihanmeeting-search">
    <div class="card card-outline card-default">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get'
        ])?>

        <div class="card-header">
            <div class="card-title">Search</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($searchModel, 'nama_produk')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Produk -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(false)?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($searchModel, 'deadline')->widget(DatePicker::className(), [
                        'options' => [
                            'placeholder' => '- Pilih bulan deadline -',
                        ],
                        'pluginOptions' => [
                            'minViewMode' => 'months',
                            'autoclose' => true,
                            'format' => 'yyyy-mm'
                        ],
                    ])->label(false)?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-block'])?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>

<div class="tagihanmeeting-index">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Tagihan Meeting Registrasi</div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped table-responsive'
                ],
                'layout' => '{items}{pager}',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'header' => 'Nama Produk',
                        'value' => function ($data) {
                            return $data->kategori->meeting->nama_produk;
                        }
                    ],
                    [
                        'header' => 'Kategori',
                        'value' => function ($data) {
                            return $data->kategori->jenisMeeting->deskripsi;
                        }
                    ],
                    [
                        'header' => 'Detail Kategori',
                        'value' => function ($data) {
                            return $data->kategori->deskripsi;
                        }
                    ],
		    'dokumen',
		    'note_rnd',
                    'deadline',
                    'status_pic',
                    [
                        'header' => 'Lampiran',
                        'value' => function ($data) {
                            if ($data->nama_dok != null) {
                                return Html::a($data->nama_dok, ['download', 'id' => $data->id]);
                            } else {
                                return '<strong class="text-red">Lampiran belum terupload</strong>';
                            }
                        },
                        'format' => 'raw',
                    ],

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
                                $url = Url::to(['meetingjustifikasi/create', 'id' => $model->kategori->meeting->id, 'kat' => $model->kategori->id, 'dok' => $model->id]);
                                return Html::a('justifikasi', $url, ['class' => 'btn btn-warning btn-xs']);
                            }
                        ],
                    ],
                ],
            ]);?>
        </div>
    </div>
</div>

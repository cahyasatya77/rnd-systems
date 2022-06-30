<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Meeting Registrasi';
$this->params['breadcrumbs'][] = $this->title;

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
$list_kategori = ArrayHelper::map($jenis_kategori, 'id', 'deskripsi');
$list_pic = ArrayHelper::map($user, 'id', 'username');

$script = <<<JS
    $('#search').on('click', function(e) {
        e.preventDefault();
        $('#search-show').toggle('slow');
    });
JS;
$this->registerJs($script);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-default">
            <div class="card-header">
                <a href="#" id="search" class="btn btn-warning">Search</a>
            </div>

            <?php $form = ActiveForm::begin([
                'action' => ['meeting'],
                'method' => 'get'
            ])?>

            <div id="search-show" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($searchModel, 'nama_obat')->widget(Select2::className(), [
                                'data' => $list_produk,
                                'options' => [
                                    'placeholder' => '- Pilih Nama Produk -'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false)?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($searchModel, 'komitmen')->widget(Select2::className(), [
                                'data' => $list_kategori,
                                'options' => [
                                    'placeholder' => '- Pilih Kategori -',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false);?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($searchModel, 'pic')->widget(Select2::className(), [
                                'data' => $list_pic,
                                'options' => [
                                    'placeholder' => '- Pilih PIC -',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(false)?>
                        </div>
                        <div class="col-md-2">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-success btn-block'])?>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end()?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
            $pdfHeader = [
                'L' => [
                    'content' => '',
                ],
                'C' => [
                    'content' => 'Meeting Registrasi',
                    'font-size' => 10,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color' => '#333333'
                ],
                'R' => [
                    'content' => '',
                ],
            ];

            $pdfFooter = [
                'L' => [
                    'content' => '',
                ],
                'C' => [
                    'content' => '',
                ],
                'R' => [
                    'content' => 'Page [{PAGENO}]',
                    'font-size' => 10,
                    'color' => '#333333',
                    'font-family' => 'arial',
                ],
                'line' => true,
            ];

            $kategori = function ($model, $key, $index, $widget) {
                return [
                    'mergeColumns' => [[4, 7]],
                    'content' => [
                        4 => $model->kategori->jenisMeeting->deskripsi,
                    ],
                    'options' => ['class' => 'info table-info h6']
                ];
            }
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'tableOptions' => [
            //     'class' => 'table table-responsive',
            // ],
            'options' => [ 'style' => 'table-layout:fixed;' ],
            'formatter' => [
                'class' => 'yii\i18n\Formatter', 
                'nullDisplay' => '-',
            ],
            'exportConfig' => [
                GridView::EXCEL => [
                    'label' => 'Excel',
                    'filename' => 'Meeting-Registrasi',
                    'config' => [
                        'cssFile' => '',
                    ],
                ],
                GridView::PDF => [
                    'label' => 'Pdf',
                    'filename' => 'Meeting-Registrasi',
                    'iconOptions' => ['class' => 'text-danger'],
                    'showHeader' => true,
                    'showPageSummary' => true,
                    'showFooter' => true,
                    'showCaption' => true,
                    'config' => [
                        'mode' => 'c',
                        'format' => 'A4-L',
                        'destination' => 'I',
                        'marginTop' => 20,
                        'marginBottom' => 20,
                        'methods' => [
                            'SetHeader' => [
                                ['odd' => $pdfHeader, 'even' => $pdfHeader]
                            ],
                            'SetFooter' => [
                                ['odd' => $pdfFooter, 'even' => $pdfFooter]
                            ],
                        ],
                    ],
                    'options' => [
                        'title' => 'Meeting Registrasi',
                    ],
                    'contentBefore' => '',
                    'contentAfter' => '',
                ],
            ],
            'toolbar' => [
                '{toggleData}',
                '{export}'
            ],
            'showPageSummary' => false,
            'striped' => false,
            'pjax' => true,
            'hover' => true,
            'panel' => [
                'type' => 'info',
                'heading' => 'Meeting Registrasi',
            ],
            'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'hidden' => true,
                    'contentOptions' => [
                        'class' => 'skip-export'
                    ],
                    'headerOptions' => [
                        'class' => 'skip-export'
                    ],
                ],

                [
                    'header' => 'ID Transaksi',
                    'value' => function ($model) {
                        return $model->kategori->meeting->id_transaksi;
                    },
                    'group' => true,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                ],
                [
                    'header' => 'Nama Produk',
                    'value' => function ($model) {
                        return $model->kategori->meeting->nama_produk;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    }
                ],
                [
                    'header' => 'No NIE',
                    'value' => function ($model) {
                        return Yii::$app->runAction('report/nie', ['id' => $model->kategori->meeting->id_produk]);
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    }
                ],
                [
                    'header' => 'ED NIE',
                    'value' => function ($model) {
                        return date('d M Y', strtotime($model->kategori->meeting->ed_nie));
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    }
                ],
                [
                    'header' => 'Bentuk Sediaan',
                    'value' => function ($model) {
                        return Yii::$app->runAction('report/bentuk-sediaan', ['id' => $model->kategori->meeting->id_produk]);
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    }
                ],
                [
                    'header' => 'Submit AERO',
                    'value' => function ($model) {
                        return date('d M Y', strtotime($model->kategori->meeting->submit_aero));
                        // return $model->kategori->meeting->submit_aero;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    }
                ],
                [
                    'header' => 'Kategori Registrasi',
                    'value' => function ($model) {
                        return $model->kategori->jenisMeeting->deskripsi;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    // 'groupHeader' => function ($model, $key, $index, $widget) {
                    //     return [
                    //         'mergeColumns' => [[2, 5]],
                    //         'content' => [
                    //             2 => $model->kategori->jenisMeeting->deskripsi,
                    //         ],
                    //         // 'options' => ['class' => 'info table-info h6']
                    //     ];
                    // }
                ],
                [
                    'header' => 'Deskripsi',
                    'value' => function ($model) {
                        return $model->kategori->deskripsi;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass' => function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    // 'groupFooter' => $kategori,
                ],
                [
                    'header' => 'Dokumen',
                    'value' => function ($model) {
                        return $model->dokumen;
                    },
                ],
                [
                    'header' => 'Catatan',
                    'value' => function ($model) {
                        return $model->note_rnd;
                    },
                ],
                [
                    'header' => 'PIC',
                    'value' => function ($model) {
                        return $model->user->username;
                    },
                ],
                [
                    'header' => 'Deadline',
                    // 'attribute' => 'dead_line',
                    'value' => function ($model) {
                        return date('d M Y', strtotime($model->deadline));
                    }
                ],
                [
                    'header' => 'Deadline Old',
                    'value' => function ($model) {
                        $data = Yii::$app->runAction('report/deadline-old-meeting', ['id' => $model->id]);
                        return $data;
                    },
                    'format' => 'raw',
                ],

                [
                    'header' => 'Status',
                    'attribute' => 'status',
                ],
                [
                    'header' => 'Keterangan',
                    'attribute' => 'keterangan',
                    'value' => function ($model) {
                        if ($model->keterangan == null or $model->keterangan == '') {
                            return null;
                        } else {
                            return wordwrap($model->keterangan, 20, '<br>');
                        }
                    },
                    'format' => 'raw',
                ],
                [
                    'header' => 'Justifikasi',
                    'value' => function ($model) {
                        if (!empty($model->justifikasiDetails)) {
                            $details = $model->justifikasiDetails;
                            $alasan = [];
                            foreach ($details as $detail) {
                                $alasan[] = $detail->justifikasi->alasan_justif;
                            }
                            return wordwrap(implode(';; ', $alasan), 20, '<br>');
                        } else {
                            return null;
                        }
                    },
                    'format' => 'raw',
                ],
            ],
        ]);?>
    </div>
</div>
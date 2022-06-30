<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel banckend\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Komitmen Registrasi';
$this->params['breadcrumbs'][] = $this->title;

$list_produk = ArrayHelper::map($produk, 'id', 'nama_produk');
$list_komitmen = ArrayHelper::map($jenis_komitmen, 'id', 'jenis');
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
            'action' => ['komitmen'],
            'method' => 'get',
        ])?>
        <div id="search-show" style="display: none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'nama_obat')->widget(Select2::className(), [
                        'data' => $list_produk,
                        'options' => [
                            'placeholder' => '- Pilih Nama Produk -',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
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
        <?php ActiveForm::end();?>
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
                    'content' => 'Komitmen Registrasi',
                    'font-size' => 10,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color' => '#333333'
                ],
                'R' => [
                    'content' => '',
                ],
                // 'line' => true,
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
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
            'exportConfig' => [
                GridView::EXCEL => [
                    'label' => 'Excel',
                    'filename' => 'Komitmen-registrasi',
                    'config' => [
                        'cssFile' => '',
                    ],
                ],
                GridView::PDF => [
                    'label' => 'Pdf',
                    'filename' => 'Komitmen-registraasi',
                    'iconOptions' => ['class' => 'text-danger'],
                    'showHeader' => true,
                    'showPageSummary' => true,
                    'showFooter' => true,
                    'showCaption' => true,
                    'config' => [
                        'mode' => 'c',
                        'format' => 'A4-L',
                        'destination' => 'D',
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
                        'options' => [
                            'title' => 'Komitmen Registrasi',
                        ],
                        'contentBefore'=>'',
                        'contentAfter'=>''
                    ]
                ],
            ],
            'toolbar' => [
                '{toggleData}',
                '{export}'
            ],
            'striped' => false,
            'hover' => true,
            'panel' => [
                'type' => 'info',
                'heading' => 'Komitmen Registrasi',
            ],
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
                    'header' => 'Id Transaksi',
                    'value' => function ($model) {
                        return $model->jnsKomitmen->komitmen->id_transaksi;
                    },
                    'group' => true,
                    'groupOddCssClass'=> function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass'=>function ($model) {
                        return ['style' => 'color: none;'];
                    },
                ],
                [
                    'header' => 'Nama Obat',
                    'value' => function($model) {
                        return $model->jnsKomitmen->komitmen->nama_obat;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass'=> function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass'=>function ($model) {
                        return ['style' => 'color: none;'];
                    },
                ],
                [
                    'header' => 'Bentuk Sediaan',
                    'value' => function ($model) {
                        return $model->jnsKomitmen->komitmen->bentuk_sediaan;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass'=> function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass'=>function ($model) {
                        return ['style' => 'color: none;'];
                    },
                ],
                [
                    'header' => 'Komitmen',
                    'value' => function ($model) {
                        return $model->jnsKomitmen->jenis->jenis;
                    },
                    'group' => true,
                    'subGroupOf' => 1,
                    'groupOddCssClass'=> function ($model) {
                        return ['style' => 'color: none;'];
                    },
                    'groupEvenCssClass'=>function ($model) {
                        return ['style' => 'color: none;'];
                    },
                ],
                [
                    'header' => 'Tindakan',
                    'attribute' => 'dokumen',
                ],
                [
                    'header' => 'PIC',
                    // 'attribute' => 'pic',
                    'value' => function ($model) {
                        return $model->user->username;
                    }
                ],
                [
                    'header' => 'Deadline',
                    // 'attribute' => 'dead_line',
                    'value' => function ($model) {
                        return date('d M Y', strtotime($model->dead_line));
                    }
                ],
                [
                    'header' => 'Deadline Old',
                    'value' => function ($model) {
                        $data = Yii::$app->runAction('report/deadline-old', ['id' => $model->id]);
                        return $data;
                    },
                    'format' => 'raw',
                ],
                [
                    'header' => 'Status Tindakan',
                    'attribute' => 'status',
                ],
                [
                    'header' => 'Keterangan',
                    'attribute' => 'keterangan',
                ],
                [
                    'header' => 'Keterangan Justifikasi',
                    'value' => function ($model) {
                        if (!empty($model->justifikasiDetails)) {
                            $details = $model->justifikasiDetails;
                            $alasan = [];
                            foreach ($details as $detail) {
                                $alasan[] = $detail->justifikasi->alasan_justif;
                            }
                            return wordwrap(implode(';; ', $alasan), 20, '<br>');
                        } else {
                            return '-';
                        }
                    },
                    'format' => 'raw',
                ],
            ],
        ])?>
    </div>
</div>
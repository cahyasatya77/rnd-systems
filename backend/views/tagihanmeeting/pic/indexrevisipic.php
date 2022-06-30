<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TagihanmeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="revisidokumenmeeting-index">
    <div class="card card-outline-card-primary">
        <div class="card-header">
            <div class="card-title">Revisi dokumen meeting</div>
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
                    'deadline',
                    [
                        'header' => 'Detail Revisi',
                        'value' => function ($data) {
                            $rev_pic = null;
                            $rev_rnd = null;
                            $rev_mng_rnd = null;
                            if ($data->revisi_mng_pic != null) {
                                $rev_pic = 'Manager pic : '.$data->revisi_mng_pic;
                            }
                            if ($data->revisi_reg != null) {
                                $rev_rnd = 'Registrasi : '.$data->revisi_reg;
                            }
                            if ($data->revisi_mng_rnd != null) {
                                $rev_mng_rnd = 'Manager rnd : '.$data->revisi_mng_rnd;
                            }

                            return $rev_pic.'<br>'.$rev_rnd.'<br>'.$rev_mng_rnd;
                        },
                        'format' => 'raw'
                    ],

                    [
                        'header' => 'Revisi',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{revisi}',
                        'buttons' => [
                            'revisi' => function ($url, $model) {
                                $url = Url::to(['updaterevisi', 'id' => $model->id]);
                                return Html::a('revisi', $url, ['class' => 'btn btn-warning btn-xs']);
                            }
                        ],
                        'visibleButtons' => [
                            'tindakan' => function ($model) {
                                if ($model->status_pic == 'revisi') {
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
                                $url = Url::to(['kirimrevisi', 'id' => $model->id]);
                                return Html::a('kirim', $url, [
                                    'class' => 'btn btn-success btn-xs',
                                    'data' => [
                                        'confirm' => 'Are you sure want to send this item to your manager ?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]);?>
        </div>
    </div>
</div>
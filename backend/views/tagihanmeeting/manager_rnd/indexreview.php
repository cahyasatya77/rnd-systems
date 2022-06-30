<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingdokumenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Review Meeting Dokumen';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reviewdokumen-index">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">List Review</div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped'
                ],
                'layout' => '{items}{pager}',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'header' => 'ID',
                        'value' => function($data) {
                            return $data->kategori->meeting->id_transaksi;
                        },
                    ],
                    [
                        'header' => 'Produk',
                        'value' => function ($data) {
                            return $data->kategori->meeting->nama_produk;
                        }
                    ],
                    [
                        'header' => 'Jenis Notulen',
                        'value' => function ($data) {
                            return $data->kategori->jenisMeeting->deskripsi.' : '.$data->kategori->deskripsi;
                        }
                    ],
                    [
                        'header' => 'Dokumen',
                        'attribute' => 'dokumen',
		    ],
		    [
		    	'header' => 'Catatan',
			'attribute' => 'note_rnd',
		    ],
                    [
                        'attribute' => 'pic',
                        'value' => function ($data) {
                            return $data->user->username;
                        }
                    ],

                    [
                        'header' => 'Review',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{review}',
                        'buttons' => [
                            'review' => function ($url, $model) {
                                $url = Url::to(['reviewrndmanager', 'id' => $model->id]);
                                return Html::a('review', $url, ['class' => 'btn btn-success btn-xs']);
                            }
                        ],
                    ],
                ],
            ])?>
        </div>
    </div>
</div>

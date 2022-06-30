<?php

use common\models\Regkomtindakan;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Registrasikomitmen */

$this->title = $model->nama_obat;
$this->params['breadcrumbs'][] = ['label' => 'Komitmen Registrasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-title">Produk</div>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-outline-danger btn-sm float-right'])?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id_transaksi',
                                'bentuk_sediaan',
                                'keterangan',
                            ],
                        ]);?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'bentuk_sediaan',
                                [
                                    'label' => 'Status',
                                    'attribute' => 'status.deskripsi'
                                ],
                            ],
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($model->jenisKomitmen as $index => $komitmen):?>
    <?php
        $dataProvider = new ActiveDataProvider([
            'query' => Regkomtindakan::find()->where(['id_jns_komitmen' => $komitmen->id]),
            'pagination' => false,
        ]);    
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title"><?= $komitmen->jenis->jenis.' ('.$komitmen->status.')'.' / Deadline : '.$komitmen->deadline_komit ?></div>
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

                            'dokumen',
                            'jenis_dokumen',
                            [
                                'header' => 'PIC',
                                'value' => function ($data) {
                                    return $data->user->username;
                                }
                            ],
                            [
                                'header' => 'Deadline',
                                'value' => function ($data) {
                                    return date('d F Y', strtotime($data->dead_line));
                                }
                            ],
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
                            'status',
                            
                            [
                                'header' => 'Detail Approve',
                                'class' => 'yii\grid\ActionColumn',
                                'contentOptions' => ['class' => 'text-center'],
                                'headerOptions' => ['class' => 'text-center'],
                                'template' => '{detail}',
                                'buttons' => [
                                    'detail' => function ($url, $model) {
                                        $url = Url::to(['viewapprove', 'id' => $model->id]);
                                        return Html::a('view', $url, ['class' => 'btn btn-info btn-xs']);
                                    }
                                ],
                            ],
                        ],
                    ]);?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach;?>


<?php $this->registerCssFile('@web/css/cahya.css');?>

<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Mastervariasi */

$this->title = $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Kode Variasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mastervariasi-view">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <div class="card-title">Kategori Variasi</div>
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-warning float-right']) ?>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'kode',
                            'deskripsi:ntext',
                            'status'
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-default">
                <div class="card-header">
                    <div class="card-title">Dokumen yang dibutuhkan</div>
                </div>
                <div class="card-body p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => '{items}{pager}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'header' => 'Kode',
                                'value' => function ($model) {
                                    return $model->masterdokumen->kode;
                                }
                            ],
                            [
                                'header' => 'Deskripsi',
                                'value' => function ($model) {
                                    return $model->masterdokumen->deskripsi;
                                },
                                'contentOptions' => ['class' => 'text-wrap']  
                            ],
                        ],
                    ]);?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->registerCssFile('@web/css/cahya.css')?>

<?php

use common\models\Masterdokumen;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MasterdokumenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Dokumen';
$this->params['breadcrumbs'][] = $this->title;

$script = <<<JS
    $('#search').on('click', function(e) {
        e.preventDefault();
        $('#search-show').toggle('slow');
    });
JS;
$this->registerJs($script);
?>

<div class="masterdokumen-index">

<div class="row pb-2">
    <div class="col-md-12">
        <a href="#" id="search" class="btn btn-warning">Search</a>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <div id="search-show" style="display: none;">
            <?= $this->render('_search', [
                'model' => $searchModel
            ]); ?>
        </div>
    </div>
</div>
<!-- <br> -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Dokumen yang dibutuhkan</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    // 'tableOptions' => [
                    //     'class' => 'table table-striped',
                    // ],
                    'layout' => '{items}{pager}',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'kode',
                        'deskripsi:ntext',
                        'status',

                        [
                            'header' => 'Action',
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Masterdokumen $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'template' => '{view}{update}'
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

</div>

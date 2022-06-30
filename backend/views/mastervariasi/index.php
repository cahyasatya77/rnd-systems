<?php

use common\models\Mastervariasi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MastervariasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Variasi';
$this->params['breadcrumbs'][] = $this->title;

$script = <<<JS
    $('#search').on('click', function(e) {
        e.preventDefault();
        $('#search-show').toggle('slow');
    });
JS;
$this->registerJs($script);
?>

<div class="mastervariasi-index">
<div class="row pb-2">
    <div class="col-md-12">
        <a href="#" id="search" class="btn btn-warning">Seach</a>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <div id="search-show" style="display: none;">
            <?= $this->render('_search', [
                'model' => $searchModel
            ]); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Kategori Variasi</div>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    // 'tableOptions' => [
                    //     'class' => 'table table-striped table-bordered',
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
                            'urlCreator' => function ($action, Mastervariasi $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                            'template' => '{view}{update}',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
</div>

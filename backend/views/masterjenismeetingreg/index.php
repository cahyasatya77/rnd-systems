<?php

use common\models\Masterjenismeetingreg;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MasterjenismeetingregSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Masterjenismeetingregs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="masterjenismeetingreg-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title">Master Jenis</div>
                    <div class="float-right">
                        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-striped'
                        ],
                        'layout' => '{items}{pager}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'deskripsi',
                            [
                                'header' => 'Actions',
                                'class' => ActionColumn::className(),
                                'urlCreator' => function ($action, Masterjenismeetingreg $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

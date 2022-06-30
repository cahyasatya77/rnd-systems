<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Masterjeniskomitmen */

$this->title = $model->jenis;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Komitmen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="masterjeniskomitmen-view">
    <div class="card card-outline card-info">
        <div class="card-header">
            <?= Html::a('Index', ['index'], ['class' => 'btn btn-default btn-sm']);?>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'jenis',
                    [
                        'attribute' => 'created_at',
                        'value' => function ($data) {
                            return date("d F Y H:i:s", $data->created_at);
                        },
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => function ($data) {
                            return date("d F Y H:i:s", $data->updated_at);
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

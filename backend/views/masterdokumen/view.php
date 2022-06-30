<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Masterdokumen */

$this->title = $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Master Dokumen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="masterdokumen-view">
    <div class="card card-info card-outline">
        <div class="card-header">
            <div class="card-title">View</div>
            <?= Html::a('index', ['index'], ['class' => 'btn btn-outline-secondary float-right'])?>
        </div>
        <div class="card-body p-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'kode',
                    'deskripsi:ntext',
                    'status',
                ],
            ]) ?>
        </div>
    </div>
</div>

<?php $this->registerCssFile('@web/css/cahya.css')?>

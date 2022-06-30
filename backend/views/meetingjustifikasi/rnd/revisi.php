<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingjustifikasi */
/* $form yii\widgets\ActiveForm */

$this->title = 'Revisi';
$this->params['breadcrumbs'][] = ['label' => 'justifikasi', 'url' => ['indexreview']];
$this->params['breadcrumbs'][] = ['label' => 'approve', 'url' => ['approvernd', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'disable-submit-buttons',
    ],
])?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <div class="card-title">Revisi Justifikasi</div>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'revisi_rnd')->textarea(['rows' => 6])->label('Revisi');?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <?= Html::a('Back', ['approvernd', 'id' => $model->id], ['class' => 'btn btn-danger'])?>
                        <?= Html::submitButton('Save', [
                            'class' => 'btn btn-success float-right',
                            'data' => ['disabled-text' => 'Validating...']
                        ])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end()?>
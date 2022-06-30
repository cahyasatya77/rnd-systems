<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\SignupForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$list_dept = ArrayHelper::map($dept, 'id', 'nama_dept');
$list_section = ArrayHelper::map($section, 'id', 'name_section');

$this->title = 'Create';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="card-title">Create New User</div>
    </div>
    <?php $form = ActiveForm::begin()?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'username')->textInput()?>

                <?= $form->field($model, 'email')?>

                <?= $form->field($model, 'password')->passwordInput()?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'department')->widget(Select2::className(), [
                    'data' => $list_dept,
                    'options' => [
                        'placeholder' => '- Pilih Department -'
                    ],
                ])?>

                <?= $form->field($model, 'section')->widget(Select2::className(), [
                    'data' => $list_section,
                    'options' => [
                        'placeholder' => '- Pilih Section -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])?>

                <?= $form->field($model, 'level_access')->widget(Select2::className(), [
                    'data' => [
                        'manager' => 'Manager',
                        'section_head' => 'Section Head',
                        'staff' => 'Staff',
                        'operator' => 'Operator',
                    ],
                    'options' => [
                        'placeholder' => '- Pilih Level Access -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'status')->widget(Select2::className(), [
                    'data' => [
                        '9' => 'In-Active',
                        '10' => 'Active'
                    ],
                ])?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
        </div>
    </div>
    <?php ActiveForm::end();?>
</div>
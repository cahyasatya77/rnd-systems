<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\model\View */
/* @var $model backend\models\UpdateUser */
/* @var $model_old common\models\User */

$list_dept = ArrayHelper::map($dept, 'id', 'nama_dept');
$lis_section = ArrayHelper::map($section, 'id', 'name_section');

$model->department = $model_old->id_dept;
$model->section = $model_old->id_section;
$model->level_access = $model_old->level_access;
$model->status = $model_old->status;

$this->title = 'Update user : '.$model_old->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_old->username, 'url' => ['view', 'id' => $model_old->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">
    <div class="card card-warning card-outline">
        <div class="card-header">
            <div class="card-title">Update User : <?= $model_old->username?></div>
        </div>

        <?php $form = ActiveForm::begin();?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'username')->textInput(['value' => $model_old->username]);?>

                    <?= $form->field($model, 'email')->textInput(['value' => $model_old->email])?>

                    <?= $form->field($model, 'password')->passwordInput()?>

                    <?= $form->field($model, 'confirm_password')->passwordInput()?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'department')->widget(Select2::className(), [
                        'data' => $list_dept,
                        'options' => [
                            'placeholder' => '- Pilih Department -'
                        ],
                    ])?>

                    <?= $form->field($model, 'section')->widget(Select2::className(), [  
                        'data' => $lis_section,
                        'options' => [
                            'placeholder' => '- Pilih Department -'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])?>

                    <?= $form->field($model, 'level_access')->widget(Select2::className(), [
                        'data' => [
                            'administrator' => 'Administrator',
                            'manager' => 'Manager',
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
            <div class="row">
                <div class="col-md-6">
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-danger'])?>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success'])?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end();?>

    </div>
</div>
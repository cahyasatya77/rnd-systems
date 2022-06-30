<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

// use yii\bootstrap4\ActiveForm;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Login';
?>
<div class="login-content">
    <?php $form = ActiveForm::begin([
        // 'id' => 'login-form',
        // 'validateOnBlur'=>false,
        // 'enableAjaxValidation'=>true,
        // 'validateOnChange'=>false,
        'fieldConfig' => [
            'template' => '{label}{input}{hint}{error}', 
        ],
    ])?>
    <img src="<?= Yii::$app->request->baseUrl.'/img/avatar.svg'?>">
    <h2 class="title">Welcome</h2>
    <a>Research And Development Systems</a>
    <div class="input-div one">
        <i class="i">
            <i class="fas fa-user"></i>
        </i>
        <?= $form->field($model, 'username')->textInput([
            // 'autofocus' => true,
            'class' => 'div',
        ])
        ->input('username',['placeholder' => 'Username', 'class' =>'input'])
        ->hint('test',[
            'class' => 'hint'
        ])
        ->label(false)?>
    </div>
    <div class="input-div pass">
        <div class="i"> 
            <i class="fas fa-lock"></i>
        </div>
            <!-- <h5 class='label'>Password</h5> -->
        <?= $form->field($model, 'password')->passwordInput([
            'class' => 'div'
        ])->input('password', ['placeholder' => 'Password', 'class' => 'input'])->label(false)?>
    </div>
    <?= Html::submitButton('Login', ['class' => 'btn', 'name' => 'login-button']) ?>
    <!-- <form action="login" method="POST">
        <img src="< ?= Yii::$app->request->baseUrl.'/img/avatar.svg'?>">
        <h2 class="title">Welcome</h2>
        <div class="input-div one">
            <div class="i">
                <i class="fas fa-user"></i>
            </div>
            <div class="div">
                <h5>Username</h5>
                <input type="text" class="input">
            </div>
        </div>
        <div class="input-div pass">
            <div class="i"> 
                <i class="fas fa-lock"></i>
            </div>
            <div class="div">
                <h5>Password</h5>
                <input type="password" class="input">
            </div>
        </div>
        <a href="#">Forgot Password?</a>
        <input type="submit" class="btn" value="Login">
    </form> -->
    <?php ActiveForm::end();?>
</div>

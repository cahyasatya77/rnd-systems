<?php

/* @var $this yii\web\View */
/* @var $model common\models\User */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'User Profile';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-profile">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" 
                            src="<?= Yii::$app->request->baseUrl.'/img/avatar.svg'?>" 
                            alt="user profile picture">
                    </div>

                    <h3 class="profile-username text-center"><?= $model->username;?></h3>

                    <p class="text-muted text-center"><?= date("Y-m-d H:i:s", $model->created_at)?></p>

                    <?= Html::a('Sign Out', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-primary btn-block'])?>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <div class="card-title">About Me</div>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Department</strong>

                    <p class="text-muted">
                        <?= Yii::$app->user->department;?>
                    </p>

                    <hr>

                    <strong><i class="fas fa-pencil-alt mr-1"></i> Level Access</strong>

                    <p class="text-muted">
                        <?= $model->level_access ? $model->level_access :'kosong'?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a href="#activity" class="nav-link active" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a href="#settings" class="nav-link" data-toggle="tab">Setting</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="activity">
                            <!-- Post -->
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="<?= Yii::$app->request->baseUrl.'/img/avatar.svg'?>" alt="user image">
                                    <span class="username">
                                    <a href="#"><?= Yii::$app->user->username?></a>
                                    </span>
                                    <span class="description">Join to R&D system - <?= date('Y-m-d', $model->created_at)?></span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Halo <?= Yii::$app->user->username;?> dari <?= Yii::$app->user->department?> department.
                                    Kamu telah bergabung pada sistem Research and Development department sejak <?= date('d F Y', $model->created_at)?>.
                                    Jika menginginkan untuk mengubah password kamu dapat mengakses menu setting pada menu diatas.
                                    Pengubahan password input password baru dan ulangi password agar password terkonfirmasi dengan benar. 
                                    Terimakasih telah menggunakan RDS (Research and Development System).
                                </p>
                            </div>
                            <!-- /.post -->
                        </div>

                        <div class="tab-pane" id="settings">
                            <?php $form = ActiveForm::begin([
                                'layout' => 'horizontal',
                                'fieldConfig' => [
                                    'horizontalCssClasses' => [
                                        'label' => 'col-sm-3',
                                        'offset' => 'col-sm-offset-2',
                                        'wrapper' => 'col-sm-9',
                                    ],
                                ],
                            ]);?>

                                <?= $form->field($new_model, 'password', [
                                    'inputOptions' => [
                                        'placeholder' => $model->getAttributeLabel('password'),
                                    ]
                                ])->passwordInput();?>

                                <?= $form->field($new_model, 'confirm_password', [
                                    'inputOptions' => [
                                        'placeholder' => $model->getAttributeLabel('confirm_password')
                                    ],
                                ])->passwordInput();?>

                                <div class="form-group row">
                                    <div class="offset-sm-3 col-sm-10">
                                        <?= Html::submitButton('Submit', ['class' => 'btn btn-danger'])?>
                                    </div>
                                </div>
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

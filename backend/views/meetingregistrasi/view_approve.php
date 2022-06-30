<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Registrasikomitmen */

$this->title = 'Detail Approve Komitmen';
$this->params['breadcrumbs'][] = ['label' => 'index', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'view', 'url' => ['view', 'id' => $model->kategori->meeting->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasikomitmen-view-approve">
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="timeline timeline-inverse">
                        <div class="time-label">
                            <span class="bg-danger">
                                <?= date('d M Y', strtotime($model->kategori->meeting->tanggal_pembuatan));?>
                            </span>
                        </div>
                        <div>
                            <i class="fas fa-envelope bg-primary"></i>

                            <div class="timeline-item">

                                <h3 class="timeline-header"><a href="#">R&D Registrasi</a> create notulen meeting</h3>

                                <div class="timeline-body">
                                    <?= $model->dokumen; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($model->kategori->meeting->approve_manager !== null) :?>
                            <div class="time-label">
                                <span class="bg-success"><?= date('d M Y', strtotime($model->kategori->meeting->approve_manager));?></span>
                            </div>
                            <div>
                                <i class="fas fa-user bg-info"></i>

                                <div class="timeline-item">
                                    <h3 class="timeline-header"><a href="#">R&D Manager</a> approve</h3>

                                    <div class="timeline-body">
                                        <p>Ketika R&D Approve notulen meeting yang dibuat oleh Registrasi, maka notulen meeting akan terkirim ke PIC terkait.</p>
                                    </div>
                                </div>
                            </div>
                            <?php if ($model->tanggal_submit != null) :?>
                                <div class="time-label">
                                    <span class="bg-primary">
                                        <?= date('d M Y', strtotime($model->tanggal_submit));?>
                                    </span>
                                </div>
                                <div>
                                    <i class="fas fa-comments bg-warning"></i>

                                    <div class="timeline-item">
                                        <h3 class="timeline-header"><a href="#"><?= $model->user->username; ?></a> Submit dokumen</h3>
                                        <?php
                                            $manager = User::findOne($model->pic_manager);
                                        ?>
                                        <div class="timeline-body">
                                            <p><?= $model->user->username; ?> telah selesai pengerjaan notulen meeting registrasi. Dan pekerjaan akan dikirimkan kepada menager terkait <strong class="text-info"> ( <?=$manager->username;?> ) </strong> untuk dilakukan review dan approve komitmen.</p>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($model->pic_manager_approve != null) :?>
                                    <div class="time-label">
                                        <span class="bg-success"><?= date('d M Y', strtotime($model->pic_manager_approve));?></span>
                                    </div>
                                    <div>
                                        <i class="fas fa-user bg-info"></i>

                                        <div class="timeline-item">
                                            <h3 class="timeline-header"><a href="#"><?= $manager->username ?></a> approve</h3>

                                            <div class="timeline-body">
                                                <p>Pemenuhan notulen meeting registrasi talah disetujui oleh manager PIC. Proses selanjutnya akan dilakukan review oleh R&D Registrasi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($model->registrasi_approve != null) :?>
                                        <div class="time-label">
                                            <span class="bg-info">
                                                <?= date('d M Y', strtotime($model->registrasi_approve));?>
                                            </span>
                                        </div>
                                        <div>
                                            <i class="fas fa-envelope bg-primary"></i>

                                            <div class="timeline-item">

                                                <h3 class="timeline-header"><a href="#">R&D Registrasi</a> Approve</h3>

                                                <div class="timeline-body">
                                                    <p>R&D Registrasi telah melakukan review terhadap pemenuhan notulen meeting PIC ( <strong class="text-info"><?= $model->user->username?></strong> ). Dan telah dilakukan approve, proses selanjutnya akan dilakukan review oleh R&D manager.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($model->manager_rnd_approve != null):?>
                                            <div class="time-label">
                                                <span class="bg-success"><?= date('d M Y', strtotime($model->manager_rnd_approve));?></span>
                                            </div>
                                            <div>
                                                <i class="fas fa-user bg-success"></i>

                                                <div class="timeline-item">
                                                    <h3 class="timeline-header"><a href="#">R&D Manager</a> approve</h3>

                                                    <div class="timeline-body">
                                                        <p>Pemenuhan notulen meeting registrasi telah disetujui, tagihan notulen meeting ditutup dan mengubah status kominmen menjadi <strong class="text-success">Done</strong></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    <?php endif;?>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endif;?>
                        <div>
                            <i class="far fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= Html::a('Back', ['view', 'id' => $model->kategori->meeting->id], ['class' => 'btn btn-danger btn-block'])?>
        </div>
    </div>
    
</div>

<?php $this->registerCssFile('@web/css/cahya.css');?>
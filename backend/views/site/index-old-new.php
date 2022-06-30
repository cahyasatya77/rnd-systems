<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Research and Development Systems';
?>
<div class="site-index">

<?php if(Yii::$app->user->idDept == 'D0001') : ?>
    <?php if (Yii::$app->user->level == 'manager') :?>
        <div class="divider-text">Komitmen Registasi</div>
        <div class="row">
            <div id="mydiv" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-info"><i class="fas fa-bookmark"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_komit);?>
                            </h3>
                            <span class="info-box-text">APPROVE KOMITMEN</span>
                        </div>
                        <?= Html::a('ne', ['registrasikomitmen/indexmanager'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="appreview" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-teal"><i class="fas fa-user-check"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($review_rnd_manager);?>
                            </h3>
                            <span class="info-box-text">REVIEW KOMITMEN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/indexreviewrndmanager'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="appjustif" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-purple"><i class="fas fa-check-double"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <div class="row">
                            <div class="col-md-5">
                                <h3>
                                    <?= count($app_justifikasi);?>
                                </h3>
                            </div>
                            <div class="col-md-7 text-right">APPROVE</div>
                            </div>
                            <span class="info-box-text">KOMITMEN JUSTIFIKASI</span>
                        </div>
                        <?= Html::a('ne', ['justifikasi/indexapprovepic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>

        <div class="divider-text">Meeting Registrasi</div>
        <div class="row">
            <div id="appmeeting" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-info"><i class="fas fa-bookmark"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_meeting);?>
                            </h3>
                            <span class="info-box-text">APPROVE MEETING</span>
                        </div>
                        <?= Html::a('ne', ['meetingregistrasi/indexmanager'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="reviewmeeting" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-teal"><i class="fas fa-user-check"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($review_meeting_rnd_manager);?>
                            </h3>
                            <span class="info-box-text">REVIEW MEETING</span>
                        </div>
                        <?= Html::a('ne', ['tagihanmeeting/indexreviewrndmanager'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="appjustifmeeting" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-purple"><i class="fas fa-check-double"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <div class="row">
                            <div class="col-md-5">
                                <h3>
                                    <?= count($app_justif_meeting);?>
                                </h3>
                            </div>
                            <div class="col-md-7 text-right">APPROVE</div>
                            </div>
                            <span class="info-box-text">MEETING JUSTIFIKASI</span>
                        </div>
                        <?= Html::a('ne', ['meetingjustifikasi/indexapprovepic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php if (Yii::$app->user->idSection == 'D0001SC001') : ?>
            <div class="divider-text">Komitmen Registasi</div>
            <div class="row">
                <div id="mydiv" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-danger"><i class="fas fa-history"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($revisi_komit);?>
                                </h3>
                                <span class="info-box-text">REVISI KOMITMEN</span>
                            </div>
                            <?= Html::a('ne', ['registrasikomitmen/revisiindex'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>

                <div id="reviewkom" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-lime"><i class="fas fa-copy"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($review_rnd);?>
                                </h3>
                                <span class="info-box-text">REVIEW KOMITMEN</span>
                            </div>
                            <?= Html::a('ne', ['tagihan/indexreview'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
            <div class="divider-text">Meeting Registasi</div>
            <div class="row">
                <div id="meetingrevisi" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-danger"><i class="fas fa-history"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($revisi_meeting);?>
                                </h3>
                                <span class="info-box-text">REVISI MEETING</span>
                            </div>
                            <?= Html::a('ne', ['meetingregistrasi/indexrevisi'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>

                <div id="reviewmeeting" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-lime"><i class="fas fa-copy"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($review_meeting_rnd);?>
                                </h3>
                                <span class="info-box-text">REVIEW MEETING</span>
                            </div>
                            <?= Html::a('ne', ['tagihanmeeting/indexreview'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
        <?php else :?>
            <!-- staff rnd selain registrasi -->
        <?php endif;?>
    <?php endif;?>
<?php else : ?>
    <?php if (Yii::$app->user->level == 'manager') :?>
        <div class="row">
            <div id="appjustif" class="col-md-4 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-teal"><i class="fas fa-user-check"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_justifikasi);?>
                            </h3>
                            <span class="info-box-text">APPROVE JUSTIFIKASI KOMITMEN</span>
                        </div>
                        <?= Html::a('ne', ['justifikasi/indexapprovepic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    <?php else : ?>

    <?php endif;?>
<?php endif;?>

</div>

<?php 

$this->registerCssFile('@web/css/cahya.css');
// $this->registerCss("
//     #logo {
//         opacity: 0;
//     }
// ");
$js = <<<JS
    // Komitmen Registrasi
    $("#mydiv").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $("#tag").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $("#apptag").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $("#revisitag").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $("#reviewkom").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $("#appreview").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $("#appjustif").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    // Meeting Registrasi
    $("#appmeeting").click(function() {
        window.location = $(this).find("a").attr("href");
    });
    $("#meetingrevisi").click(function() {
        window.location = $(this).find("a").attr("href");
    });
    $("#reviewmeeting").click(function() {
        window.location = $(this).find("a").attr("href");
    });
    $("#appjustifmeeting").click(function() {
        window.location = $(this).find("a").attr("href");
    });

    $(document).ready(function() {
        $(".col-md-3.col-sm-3.col-xs-12").on("mouseenter", function() {
            $(this).find(".info-box").addClass("bg-gray")
        }).on("mouseleave", function() {
            $(this).find(".info-box").removeClass("bg-gray")
        });
    });
JS;
$this->registerJs($js);

?>

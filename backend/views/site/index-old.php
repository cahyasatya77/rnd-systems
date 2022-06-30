<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Research and Development Systems';
?>
<div class="site-index">

<?php if(Yii::$app->user->idDept == 'D0001') : ?>
    <?php if (Yii::$app->user->level == 'manager') :?>
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

            <div id="tag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-file-invoice"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($tagihan_komit);?>
                            </h3>
                            <span class="info-box-text">TAGIHAN KOMITMEN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/index'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="apptag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-purple"><i class="fas fa-check-double"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_tagihan);?>
                            </h3>
                            <span class="info-box-text">APPROVE TAGIHAN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/indexapprove'], ['class' => 'btn btn-success btn-sm d-none'])?>
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
                            <span class="info-box-text">APPROVE REVIEW</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/indexreviewrndmanager'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <div class="row">
            <div id="appjustif" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-teal"><i class="fas fa-user-check"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_justifikasi);?>
                            </h3>
                            <span class="info-box-text">APPROVE JUSTIFIKASI</span>
                        </div>
                        <?= Html::a('ne', ['justifikasi/indexapprovepic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php if (Yii::$app->user->idSection == 'D0001SC001') : ?>
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

                <div id="tag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-yellow"><i class="fas fa-file-invoice"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($tagihan_komit);?>
                                </h3>
                                <span class="info-box-text">TAGIHAN KOMITMEN</span>
                            </div>
                            <?= Html::a('ne', ['tagihan/index'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>

                <div id="revisitag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-danger"><i class="fas fa-history"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($revisi_tagihan);?>
                                </h3>
                                <span class="info-box-text">REVISI TAGIHAN</span>
                            </div>
                            <?= Html::a('ne', ['tagihan/indexrevisipic'], ['class' => 'btn btn-success btn-sm d-none'])?>
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
        <?php else :?>
            <div class="row">
                <div id="tag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-yellow"><i class="fas fa-file-invoice"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($tagihan_komit);?>
                                </h3>
                                <span class="info-box-text">TAGIHAN KOMITMEN</span>
                            </div>
                            <?= Html::a('ne', ['tagihan/index'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>

                <div id="revisitag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-danger"><i class="fas fa-history"></i></span>

                        <div class="info-box-content">
                            <div class="inner">
                                <h3>
                                    <?= count($revisi_tagihan);?>
                                </h3>
                                <span class="info-box-text">REVISI TAGIHAN</span>
                            </div>
                            <?= Html::a('ne', ['tagihan/indexrevisipic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>
<?php else : ?>
    <?php if (Yii::$app->user->level == 'manager') :?>
        <div class="row">
            <div id="tag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-file-invoice"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($tagihan_komit);?>
                            </h3>
                            <span class="info-box-text">TAGIHAN KOMITMEN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/index'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="apptag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-purple"><i class="fas fa-check-double"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_tagihan);?>
                            </h3>
                            <span class="info-box-text">APPROVE TAGIHAN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/indexapprove'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="appjustif" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-teal"><i class="fas fa-user-check"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($app_justifikasi);?>
                            </h3>
                            <span class="info-box-text">APPROVE JUSTIFIKASI</span>
                        </div>
                        <?= Html::a('ne', ['justifikasi/indexapprovepic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="row">
            <div id="tag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-file-invoice"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($tagihan_komit);?>
                            </h3>
                            <span class="info-box-text">TAGIHAN KOMITMEN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/index'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div id="revisitag" class="col-md-3 col-sm-3 col-xs-12" style="cursor: pointer;">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-danger"><i class="fas fa-history"></i></span>

                    <div class="info-box-content">
                        <div class="inner">
                            <h3>
                                <?= count($revisi_tagihan);?>
                            </h3>
                            <span class="info-box-text">REVISI TAGIHAN</span>
                        </div>
                        <?= Html::a('ne', ['tagihan/indexrevisipic'], ['class' => 'btn btn-success btn-sm d-none'])?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>

</div>

<?php 

$this->registerCssFile('@web/css/home.css');
// $this->registerCss("
//     #logo {
//         opacity: 0;
//     }
// ");
$js = <<<JS
    $("#mydiv").click(function() {
        window.location = $(this).find("a").attr("href");
        // alert(data);
        // return false;
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

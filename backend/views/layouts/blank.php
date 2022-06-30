<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\LoginAsset;
use yii\helpers\Html;

LoginAsset::register($this);
// $this->registerCssFile('@web/css/login.css');
// $this->registerJsFile('@web/js/login.js');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<!-- Yii -->
	<meta charset="<?= Yii::$app->charset ?>">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<!-- EndYii -->
	<!-- <link rel="stylesheet" type="text/css" href="< ?= Yii::$app->request->baseUrl.'/css/login.css'?>"> -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl.'/img/favicon_p.png'; ?>" type="image/x-icon" />
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php $this->beginBody()?>
	<img class="wave" src="<?= Yii::$app->request->baseUrl.'/img/wave.png'?>">
	<div class="container">
		<div class="img">
			<img src="<?= Yii::$app->request->baseUrl.'/img/bg.svg'?>">
		</div>
		<?= $content ?>
	</div>
<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>

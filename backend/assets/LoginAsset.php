<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle for login page.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.css'
    ];
    public $js = [
        'js/login.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap4\BootstrapAsset',
    ];
}
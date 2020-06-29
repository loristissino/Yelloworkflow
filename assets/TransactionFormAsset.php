<?php

namespace app\assets;

use yii\web\AssetBundle;

class TransactionFormAsset extends AssetBundle

{

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [

        'css/transaction_form.css',

    ];

    public $js = [

        'js/transaction_form.js',
        'js/jsvat.js',

    ];

    public $depends = [

        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',

    ];

}

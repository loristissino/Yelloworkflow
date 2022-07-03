<?php

namespace app\assets;

use yii\web\AssetBundle;

class TransactionFormAsset extends AssetBundle

{

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [

        'css/transaction_form.css?v20220109',

    ];

    public $js = [

        'js/transaction_form.js?v20230703',
        'js/jsvat.js',

    ];

    public $depends = [

        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',

    ];

}

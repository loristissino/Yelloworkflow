<?php

$this->registerJs(
    'window.PWAConfig = ' .
    \yii\helpers\Json::htmlEncode(\Yii::$app->params['pwa']) . ';',
    \yii\web\View::POS_HEAD,
    'pwa-config'
);

$this->registerJsFile(
    '@web/js/digital-receipt.js',
    [
        'position'=>\yii\web\View::POS_END,
    ],
    'legacy-app-js'
);

$this->registerJsFile(
    '@web/digital-receipts/src/app.js',
    [
        'type'=>'module',
        'position'=>\yii\web\View::POS_END,
    ],
    'main-app-js'
);

$this->registerCssFile(
    '@web/css/digital_receipt_form.css',
    [],
    'digital-receipt-form-css'
);

$this->registerCssFile(
    '@web/digital-receipts/styles/style.css',
    [],
    'digital-receipt-css'
);

?>
<h1 style='display: none'>YWF APP</h1>
<div class="info"><span id="status"></span> - <span id="user">?</span></div>
<div id="app"></div>

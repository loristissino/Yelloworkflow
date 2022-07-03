<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.') ?>
    </p>
    <p>
        <?= Yii::t('app', 'Please <a href="{contact_url}">contact</a> us if you think this is a server error, or try <a href="{reload_url}">reloading</a> the page. Thank you.', ['contact_url'=>Url::toRoute(['site/about']), 'reload_url'=>Yii::$app->request->url]) ?>
    </p>

</div>

<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$contacts = Yii::$app->params['contacts'];

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'Yellow is an application to ease administrative tasks.') ?>
    </p>
    
    <h2><?= Html::encode(Yii::t('app', 'Contacts')) ?></h2>
    <ul>
        <?php foreach($contacts as $email => $contact): ?>
            <li><a href="mailto:<?= $email ?>"><?= $email ?></a> - <?= $contact ?></li>
        <?php endforeach ?>
    </ul>
    
</div>

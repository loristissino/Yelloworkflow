<?php

use yii\helpers\Html;
use yii\helpers\Markdown;

/* @var $this yii\web\View */
/* @var $model app\models\Petition */

?>
<div class="envolve petition-text">

    <h1><?= $model->title ?></h1>

    <div class="picture"><?= Yii::t('app', $image_html_template, ['url' => $model->picture_url] ) ?></div>

    <h2><?= Yii::t('app', 'Target') ?></h2>
    <div class="target"><?= Markdown::process($model->target) ?></div>

    <h2><?= Yii::t('app', 'Introduction') ?></h2>
    <div class="introduction"><?= Markdown::process($model->introduction) ?></div>

    <h2><?= Yii::t('app', 'Request') ?></h2>
    <div class="request"><?= Markdown::process($model->request) ?></div>

    <?php if (($number = $model->numberOfConfirmedSignatures) > 20): ?>
        <h2><?= Yii::t('app', 'Signatures') ?></h2>
        <div class="signatures"><p><?= Yii::t('app', '{number,plural,=0{No signature yet}=1{This petition has been signed by one person}other{This petition has been signed by # people}}.', ['number'=>$number]) ?> Vedi l'<a href="https://firma.uaar.it/zuppi-a-roma-tre-elenco-firme/">elenco completo</a>.</p></div>
        <?php if ($model->goal > 0): ?>
            <div class="goal"><p><?= Yii::t('app', '{number} signatures.', ['number'=>$model->goal]) ?></p></div>
        <?php endif ?>
    <?php endif ?>
    
    <?php if($model->updates): ?>
        <h2><?= Yii::t('app', 'Updates') ?></h2>
        <div class="updates"><?= Markdown::process($model->updates) ?></div>
    <?php endif ?>

    <hr>
    <?php if ($model->promoted_by): ?>
        <div class="promotion"><?= $model->promoted_by ?></div>
        <hr>
    <?php endif ?>

</div>

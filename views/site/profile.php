<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'Email: ') ?>
        <?= Yii::$app->user->identity->email ?>
    </p>
    
    <hr />

    <h2><?= Yii::t('app', 'API keys') ?></h2>
    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No API keys found} =1{One API key found} other{# API keys found}}:',
        'items'=>\Yii::$app->user->identity->getApiKeys()->all(),
        'textProperty'=>'view',
        'link'=>null,
    ]) ?>
    
    <?= Html::a("Create an API key", ['site/apikey', 'action'=>'create'], ['data-method'=>'post'])?>
    
</div>

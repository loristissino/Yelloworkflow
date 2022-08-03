<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;

$userAgents = Yii::$app->user->identity->userAgents;

$this->registerJs(
    "
    $('.loader').on('click', function(event){
        $('#loader').show();
    });
    ",
    \yii\web\View::POS_END,
    'loader_manager'
);

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'Email: ') ?>
        <?= Yii::$app->user->identity->email ?>
    </p>
    <p>
        <?= Yii::t('app', 'Last logins: ') ?>
        <ul>
            <?php foreach(Yii::$app->user->identity->lastLogins as $login): ?>
                <li>
                    <?= sprintf('%s %s (UTC)', Yii::$app->formatter->asDate($login->happened_at), Yii::$app->formatter->asTime($login->happened_at)); ?>
                </li>
            <?php endforeach ?>
        </ul>
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
    
    <hr />
    
    <h2><?= Yii::t('app', 'Two-factor authentication') ?></h2>
    
    <?php if (Yii::$app->user->identity->otp_secret): ?>
    
        <p>
            <?= Yii::t('app', 'Two-factor authentication enabled.') ?>
        </p>

        <p>
            <?= Html::a(Yii::t('app', 'Disable two-factor authentication'), ['site/disable-two-factor-authentication'], ['class'=>'loader'])?>
            <img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />
            <?= Yii::t('app', '(an email will be sent to confirm the process).') ?>
        </p>
      
        <?php if (sizeof($userAgents)>0): ?>

            <p>
                <?= Yii::t('app', 'Trusted devices:') ?>
                <ul>
                    <?php foreach($userAgents as $ua): ?>
                        <li>
                            <?= $ua->info ?> (<?= Html::a(Yii::t('app', 'Revoke'), ['site/revoke-ua', 'id'=>$ua->id], ['data-method'=>'post']) ?>)
                        </li>
                    <?php endforeach ?>
                </ul>
            </p>
        <?php else: ?>
            <p><?= Yii::t('app', 'No trusted device.') ?></p>
        <?php endif ?>
    
    <?php else: ?>
    
        <?= Html::a(Yii::t('app', 'Enable two-factor authentication'), ['site/enable-two-factor-authentication'])?>
    
    <?php endif ?>
    
</div>

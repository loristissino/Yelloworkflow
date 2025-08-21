<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;

$userAgents = Yii::$app->user->identity->userAgents;

$affiliations = Yii::$app->user->identity->getAffiliationsWithEmail();

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
<div class="site-profile">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2><?= Yii::t('app', 'Email') ?></h2>

    <p>
        <?= Yii::t('app', 'Personal email address:') ?> 
        <?= Yii::$app->user->identity->email ?>
    </p>
    <div>
        <?= Yii::t('app', 'Other email addresses:') ?>
        <ul>
        <?php foreach($affiliations as $affiliation): ?>
            <li>
                <div>
                    <?= Yii::t('app', 'Organizational Unit') ?>: <b><?= $affiliation->organizationalUnit ?></b><br>
                    <?= Yii::t('app', 'Role') ?>: <?= $affiliation->role ?><br>
                    <?= Yii::t('app', 'Email') ?>: <?= $affiliation->email ? $affiliation->email : $affiliation->ouEmail . ' 🧑‍🧑‍🧒‍🧒' ?><br>
                </div>
            </li>
        <?php endforeach ?>
        </ul>
        
    </div>
    
    <h2><?= Yii::t('app', 'Application Usage') ?></h2>
    
    <div>
        <?= Yii::t('app', 'Last logins:') ?>
        <ul>
            <?php foreach(Yii::$app->user->identity->lastLogins as $login): ?>
                <li>
                    <?= sprintf('%s %s (UTC)', Yii::$app->formatter->asDate($login->happened_at), Yii::$app->formatter->asTime($login->happened_at)); ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    
    <hr>
    
    <h2><?= Yii::t('app', 'Notifications') ?></h2>
    
    <p><?= Html::a(Yii::t('app', 'View'), ['notifications/index']) ?></p>
    <p><?= Html::a(Yii::t('app', 'Settings'), ['notifications/settings']) ?></p>
    
    <hr>

    <h2><?= Yii::t('app', 'API keys') ?></h2>
    <?= \app\components\UnorderedListWidget::widget([
        'introMessage'=>'{count,plural,=0{No API keys found} =1{One API key found} other{# API keys found}}:',
        'items'=>\Yii::$app->user->identity->getApiKeys()->all(),
        'textProperty'=>'view',
        'link'=>null,
    ]) ?>
    
    <?= Html::a(Yii::t('app', 'Create an API key'), ['site/apikey', 'action'=>'create'], ['data-method'=>'post'])?>
    
    <hr>
    
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

<?php

use yii\helpers\Url;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */

$this->title = Yii::$app->name;
$app_description = ArrayHelper::getValue(Yii::$app->params, 'appDescription', Yii::t('app', 'A powerful application to manage the workflow of your organization.')) ;

?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::$app->name ?></h1>
        
        <?php /*
        <?php if (Yii::$app->user->isGuest): ?>
            <p>You are not authenticated. Please <?= BaseHtml::a('login', Url::toRoute('site/login')) ?>.</p>
        <?php else: ?>
            <p>You are the user: <?php echo Yii::$app->user->id ?> (<?php echo Yii::$app->user->identity->username ?>)</p>
        <?php endif ?>
        */ ?>
        <p class="lead"><?= $app_description ?></p>

        <p><?= Html::a(Yii::t('app', 'Go'), Yii::$app->user->isGuest?['site/login']:['site/dashboard'], ['class'=>"btn btn-lg btn-success"]) ?></p>
    </div>

    <div class="body-content">

        <?php /*
        <div class="row">
            <div class="col-lg-4">
                <h2>Progetti e richieste di fi</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>
            </div>
            <div class="col-lg-4">
                <h2>Basic accounting</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>
            </div>
            <div class="col-lg-4">
                <h2>Events</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>
                    
            </div>
        </div>
        */ ?>
    </div>
</div>

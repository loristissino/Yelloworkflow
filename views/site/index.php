<?php

use yii\helpers\Url;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = Yii::$app->name;
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
        <p class="lead"><?= Yii::t('app', 'A powerful application to manage the workflow of your organization.') ?></p>

        <p><?= Html::a('Go to your Dashboard', ['site/dashboard'], ['class'=>"btn btn-lg btn-success"]) ?></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Projects and Reimbursements</h2>

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
    </div>
</div>

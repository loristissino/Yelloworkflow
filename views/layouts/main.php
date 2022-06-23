<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $items = [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
    ];
    
    if (Yii::$app->user->isGuest) {
        $items[] = ['label' => 'Login', 'url' => ['/site/login']];
    }
    else {
        $items[] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/site/dashboard']];
        
        if (Yii::$app->user->hasAuthorizationFor('users')) {
            $items[] = ['label' => Yii::t('app', 'Users and OUs'),
                'items' => [
                    ['label' => Yii::t('app', 'Users'), 'url' => ['users/index']],
                    ['label' => Yii::t('app', 'Organizational Units'), 'url' => ['organizational-units/index']],
                    ['label' => Yii::t('app', 'Roles'), 'url' => ['roles/index', 'type'=>'general']],
                    ['label' => Yii::t('app', 'Affiliations'), 'url' => ['affiliations/index']],
                    '<li class="divider"></li>',
                    ['label' => Yii::t('app', 'Renewals Check'), 'url' => ['roles/renewals']],
                    ['label' => Yii::t('app', 'Renewals Bulk Update'), 'url' => ['users/renewals']],
                ]
            ];
        }

        if (Yii::$app->user->hasAuthorizationFor('periodical-reports-management')) {
            $items[] = ['label' => Yii::t('app', 'Financial Reports Management'),
                'items' => [
                    ['label' => Yii::t('app', 'Index'), 'url' => ['periodical-reports-management/index']],
                    ['label' => Yii::t('app', 'Balances'), 'url' => ['periodical-reports-management/summary', 'type'=>'balances']],
                    ['label' => Yii::t('app', 'Recap'), 'url' => ['periodical-reports-management/recap', 'type'=>'general']],
                    ['label' => Yii::t('app', 'Submitted Periodical Reports'), 'url' => ['periodical-reports-management/recap', 'type'=>'submitted']],
                ]
            ];
        }
        
        $items[] = ['label' => Yii::t('app', 'Handbook'), 'url' => ['/site/handbook'], 'linkOptions' => ['target'=>'_blank', 'title'=>Yii::t('app', 'Opens in a new tab')]];
        $items[] = (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
                );
        }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">
            <?= Html::a('&copy; 2020-2021 LT (GNU Affero GPL)', 'https://github.com/loristissino/Yelloworkflow') ?>
            <?php /*- 
            <?php foreach(\Yii::$app->controller->authorization_ids as $id=>$value): ?>
                <?= Html::a($value, ['authorizations/view', 'id'=>$id]) ?> 
            <?php endforeach ?>
            -
            <?= Yii::$app->session->get('organizational_unit_id', Yii::t('app', 'No current Organizational Unit')) ?>
            */ ?>
        </p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

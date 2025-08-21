<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;


$this->registerJs(
    "
    let pingUrl = '". Url::toRoute(['site/ping']). "';
    
    let cnt = $('#user_info').html();

    
    async function ping() {
        let response = await fetch(pingUrl);
        let content = await response.json();
        if (!content) {
            return;
        }
        let users = content.users.map(x => x.fullName);
        let tooltip = '';
        let icon = '🙂';
        if (users.length > 0) {
            tooltip = '👥\\n' + users.join('\\n') + '\\n';
            icon = '😌';
        }
        if (content.unseen_notifications > 0) {
            tooltip += `\\n✉️ \${content.unseen_notifications}`;
            icon = '🔔';
        }
        $('#user_info').attr('title', tooltip);
        $('#user_info').html(cnt.replace('😶', icon));
    }
    
    setTimeout(ping, 1000);
    setInterval(ping, 10000);
    
    
    ",
    \yii\web\View::POS_READY,
    'pinger'
);

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
        $notifications = (int)Yii::$app->user->identity->getNumberOfUnseenNotifications();

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
                    ['label' => Yii::t('app', 'Organizational Units\' Main Activities'), 'url' => ['organizational-units/main-activities', 'daysBack'=>365]],
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
                    ['label' => Yii::t('app', 'Ceiling Amounts'), 'url' => ['organizational-units/ceiling-amounts']],
                ]
            ];
        }

        // FIXME: add customization of menu directly from users
        if (!Yii::$app->user->hasAuthorizationFor('roles') && Yii::$app->user->hasAuthorizationFor('roles', 'renewals')) {
            $items[] = ['label' => Yii::t('app', 'Users'),
                'items' => [
                    ['label' => Yii::t('app', 'Renewals Check'), 'url' => ['roles/renewals']],
                    ['label' => Yii::t('app', 'Organizational Units\' Main Activities'), 'url' => ['organizational-units/main-activities', 'daysBack'=>365]],
                ]
            ];
        }
        
        $items[] = ['label' => Yii::t('app', 'Help'),
            'items' => [
                ['label' => Yii::t('app', 'Handbook'), 'url' => ['/site/handbook'], 'linkOptions' => ['target'=>'_blank', 'title'=>Yii::t('app', 'Opens in a new tab')]],
                ['label' => Yii::t('app', 'Open an issue'), 'url' => ['/site/issues', 'reference'=>$this->title, 'url'=>urlencode(Yii::$app->request->url)]],
                ['label' => Yii::t('app', 'Contacts'), 'url' => ['/site/about']],
            ]
        ];

        $items[] = ['label' => '😶', 'options'=>['id'=>'user_info'],
            'items' => [
                ['label' => Yii::t('app', '{count,plural,=0{Notifications (empty)} =1{Notifications (one)} other{Notifications (#)}}', ['count'=>$notifications]), 'url' => ['/notifications/index']],
                ['label' => Yii::t('app', 'Profile'), 'url' => ['/site/profile']],
            ]
        ];

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
            <?= Html::a('&copy; 2020-2025 LT (GNU Affero GPL)', 'https://github.com/loristissino/Yelloworkflow') ?>
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

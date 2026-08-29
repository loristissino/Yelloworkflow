<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\PwaModulesEndAsset;
use app\assets\PwaHeadAsset;

$this->title = "Yellow PWA";
PwaModulesEndAsset::register($this);
PwaHeadAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="theme-color" content="#000000">
    <link rel="manifest" href="<?= \yii\helpers\Url::to(['app/manifest']) ?>">
    <link rel="icon" href="/images/app-icon-192.png">
    <link href="/css/app.css" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<main class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</main>    

<div class="wrap">
<footer class="footer">
    <p class="pull-left">
        <?= Html::a('&copy; 2020-2026 LT (GNU Affero GPL)', 'https://github.com/loristissino/Yelloworkflow') ?>
    </p>
    <p class="pull-right"><?= Html::a(Yii::$app->name, Yii::$app->params['baseUrl']) ?></p>
</footer>
</div>

<?= $this->render('/app/templates.php') ?>

<?php $this->endBody() ?>

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('<?= \yii\helpers\Url::to(['app/service-worker', 'v'=>'20260828c']) ?>', { type: 'module', scope: '/' })
        .then(reg => console.log('SW registered:', reg))
        .catch(err => console.error('SW registration failed:', err));
    }
</script>

</body>
</html>
<?php $this->endPage() ?>

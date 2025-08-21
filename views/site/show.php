<?php

use yii\helpers\Html;
use app\assets\AppAsset;

$this->registerJs(
    "
        let degrees = 0;
        let img = document.querySelector('img');
        img.onclick = function(){
            console.log(degrees);
            degrees+=90;
            img.style.transform = 'translate(-50%, -50%) rotate(' + degrees + 'deg)'
        }
    ",
    \yii\web\View::POS_READY,
    'imageManagement'
);

$this->title = Yii::t('app', 'Image') . ' ' . parse_url($url)['query'];

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, minimum-scale=0.1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body style="margin: 0px; height: 100%; background-color: rgb(14, 14, 14);">
        <img title="<?= Yii::t('app', 'Click to rotate')?>" style="display: block;-webkit-user-select: none;margin: auto;cursor: zoom-in;background-color: hsl(0, 0%, 90%);transition: background-color 300ms; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%)" src="<?=$url ?>">
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

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
    
    <style>
body {
  background: rgb(204,204,204);
}
page {
  background: white;
  color: black;
  font-family: Arial, sans-serif;
  font-size: 0.8em;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
page[size="A4"] {  
  width: 17cm;
  /*height: 29.7cm;*/
  padding: 2cm;
}

h1 {
  margin-block-start: 0px;
  margin-block-end: 10px;
}

h2 {
  margin-block-start: 10px;
  margin-block-end: 10px;
}

table {
    border: 1px solid black;
    border-collapse: collapse;
}

td {
    border: 1px solid black;
    padding: 0px 6px 0px 6px;
}

th {
  text-align: left;
  padding: 0px 6px 0px 6px;
}

ul {
    padding: 0px 20px 0px 20px;
}

.amount {
    text-align: right;
}

@media print {
  body, page {
    margin: 0;
    box-shadow: 0;
  }
}

    </style>
</head>
<body>
<?php $this->beginBody() ?>
    <page size="A4">
    <div class="container">
        <?= $content ?>
    </page>

<footer class="footer">
    <div class="container">
        <p class="pull-left">
            <?= Yii::$app->name ?> - <?= Yii::$app->formatter->asDate(time()) ?>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

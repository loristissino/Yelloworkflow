<?php

//use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$this->title = Yii::t('app', 'Digital Receipt') . ' ' . $id;

$color = '#EEEEFF';

\yii\web\YiiAsset::register($this);

\yii\jui\JuiAsset::register($this);

?>

<div class="digital-receipt-view">

<div class="receipt-container" style="background-color: <?=$color ?>">
    
    <header class="receipt-header">
        <div class="logo">
            <img class="logo" alt="<?= Html::encode($issuer['name']) ?>" src="<?= Url::to('@web/' .Yii::$app->params['receipts']['receipt']['logo'], true) ?>">
        </div>
        <h1 class="company-name"><?= Html::encode($issuer['name']) ?></h1>
        <div class="company-address"><?= Html::encode($issuer['address']) ?></div>
        <div class="company-identification">
            <?=Yii::t('app', 'Fiscal ID') ?>: <span class="fiscal-id"><?= Html::encode($issuer['fiscal_id']) ?></span><br>
            <?=Yii::t('app', 'VAT ID') ?>: <span class="vat-id"><?= Html::encode($issuer['vat_id']) ?></span><br>
        </div>
    </header>

    <p>
        <?= Yii::t('app', 'The data of the receipt «{id}…» have not been uploaded yet.', ['id'=>substr($id, 0, 8)]) ?>
        <?= Yii::t('app', 'Please come back later or tomorrow.', ['id]'=>$id]) ?>
    </p>

    <footer class="receipt-meta">
        <p>
                <br>
                <span class="thank-you"><?= Yii::t('app', 'Thank you!') ?><br>
                <?= Yii::$app->params['receipts']['receipt']['tagline'] ?></span>
        </p>
    </footer>
</div>
</div>

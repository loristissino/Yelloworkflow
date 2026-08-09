<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */

$this->title = Yii::t('app', 'Digital Receipt') . ' ' . $model->client_id;

$color = $model->digitalReceiptType->color;

$issuer = $model->getJsonField('issuer_data');

$voided = $model->voiding_receipt_assigned_id != null;

$voiding = $model->voided_receipt_assigned_id != null;

$vat_codes_descriptions = Yii::$app->params['receipts']['vat_codes_descriptions'];

$vat_codes=[];

$info = $model->getJsonField('api_response');
$notes = $model->getJsonField('notes');

if ($model->isReturnReceipt) {
    $items = $notes['items'];
    $totalAmount = $notes['total_amount'];
}
elseif ($voiding) {
        $infov = JSON_decode($model->voidedReceipt()->api_response, true);
        $items = $infov['data']['items'];
        $totalAmount = $infov['data']['total_amount'];
    }
else {
        $items = $info['data']['items'];
        $totalAmount = $info['data']['total_amount'];
}

\yii\web\YiiAsset::register($this);

$url = sprintf(Yii::$app->params['qrcode2fAUrl'], $model->url);

$sourceImage='data:image/png;base64,' . base64_encode(file_get_contents($url));

$img = "<img class=\"qrcode\" src=\"$sourceImage\" alt=\"" . Yii::t('app', 'QR Code') . "\">";

$qr = $img;

if (!Yii::$app->user->isGuest){
    $action = $model->getWorkflowStatus()->getId()=='DigitalReceiptWorkflow/issued'? 'update': 'view';
    $qr = Html::a($img, ['/digital-receipts/'. $action, 'id'=>$model->id], ['target'=>'_top']);
}


$dateFormat = 'dd-MM-yyyy HH:mm';

\yii\jui\JuiAsset::register($this);

?>

<div class="digital-receipt-view">

<div class="receipt-container <?= $framed ? 'framed':'' ?> <?= $voided ? 'receipt-voided':''?> <?= $model->isReturnReceipt ? 'return-receipt':''?> <?= $voiding ? 'receipt-voiding':''?>" style="background-color: <?=$color ?>">
    
    <header class="receipt-header">
        <div class="logo">
            <img class="logo" alt="<?= Html::encode($issuer['name']) ?>" src="<?= Url::to('@web/' .Yii::$app->params['receipts']['receipt']['logo'], true) ?>">
        </div>
        <h1 class="company-name"><?= Html::encode($issuer['name']) ?></h1>
        <div class="company-address"><?= Html::encode($issuer['address']) ?></div>
        <div class="company-identification">
            <?=Yii::t('app', 'Fiscal ID') ?>: <span class="fiscal-id"><?= Html::encode($issuer['fiscal_id']) ?></span><br>
            <?=Yii::t('app', 'VAT ID') ?>: <span class="vat-id"><?= Html::encode($issuer['vat_id']) ?></span><br>
            <span class="issuer"><?= Html::encode($issuer['organizational_unit']) ?></span>

        </div>
    </header>

    <hr class="dashed-line">

    <table class="receipt-items">
        <thead>
            <tr>
                <th class="col-desc"><?= Yii::t('app', 'Description') ?></th>
                <th class="col-vat"><?= Yii::t('app', 'VAT') ?></th>
                <th class="col-amount"><?= Yii::t('app', 'Amount') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): $notes=$item['notes'] ?? ''; $grossAmount = $item['quantity'] * $item['unit_price']; $discountedPrice = round(($grossAmount-$item['discount']) / $item['quantity'], 2); ?>
            <tr>
                <td class="col-desc">
                    <?=$item['description'] ?>
                    <?php if($item['quantity'] != 1): ?>
                        <div class="unit-price-sub notes"><?php //= Yii::t('app', '{pieces,plural,=0{No piece} =1{One piece} other{# pieces}}, {amount} each', ['pieces'=>$item['quantity'], 'amount'=>Yii::$app->formatter->asCurrency($item['unit_price'])]) ?>
                        <?= Yii::t('app', '{quantity} × {price} each', ['quantity'=>$item['quantity'], 'price' => Yii::$app->formatter->asCurrency($discountedPrice)]) ?></div>
                    <?php endif; ?>
                    <?php if ($notes): ?>
                        <div class="notes"><?= Html::encode($notes) ?></div>
                    <?php endif ?>
                </td>
                <td class="col-vat amount">
                    <?= $item['vat_rate_code'] ?>
                    <?php $vat_codes[$item['vat_rate_code']]=1 ?>
                </td>
                <td class="col-amount amount number">
                    <?php if ($item['discount']>0): ?>
                        <div class="discount">
                            <?= Yii::t('app', '{amount} - {discount} discount', [
                                'amount'=> Yii::$app->formatter->asCurrency($grossAmount),
                                'discount'=> Yii::$app->formatter->asCurrency($item['discount']),
                                ]) ?>
                        </div>
                    <?php endif ?>
                    <?= Yii::$app->formatter->asCurrency($item['quantity'] * $item['unit_price'] -$item['discount']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="vat-codes">
    <p>
        <?= Yii::t('app', 'VAT Codes') ?><br>
        <?php foreach(array_keys($vat_codes) as $vat_code):?>
            <?=$vat_code ?>: <?=$vat_codes_descriptions[$vat_code] ?><br>
        <?php endforeach ?>
    </p>
    </div>

    <hr class="dashed-line">

    <div class="receipt-totals">
        <table style="width: 100%">
            <tbody>
            <tr class="total-row big-total">
                <td><span><?= Yii::t('app', 'Total') ?></span></td>
                <td class="col-amount number"><span><?= Yii::$app->formatter->asCurrency($totalAmount) ?></span></td>
            </tr>
            <tr class="total-row payment-info">
                <td><?= Yii::t('app', 'VAT included') ?></td>
                <td class="col-amount number"><span><?= Yii::$app->formatter->asCurrency($model->totalVat) ?></span></td>
            </tr>
            <tr class="total-row payment-info">
                <td><?= Yii::t('app', 'Payment') ?> (<?= $model->paymentMethod ?>)</td>
                <td class="col-amount number"><span><?= Yii::$app->formatter->asCurrency($totalAmount) ?></span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr class="dashed-line">

    <footer class="receipt-meta">
        <p>
            <?=\Yii::$app->formatter->asDateTime($model->created_at, $dateFormat) ?><br>
            <?php /*<p><?= Yii::t('app', 'Status') ?>: <?= $model->getWorkflowStatus()->getLabel() ?></p><?php */ ?>
            <?php /* $model->digitalReceiptType->label ?><?php //<br><?= $info['data']['id'] ?><?php */?>
            <?= $model->documentLabel ?>
            <?= Yii::t('app', '#') ?> <?= $model->completeSequentialNumber ?>
            
            <?php if(!$voiding): ?>
                <br>
                <span class="thank-you"><?= Yii::t('app', 'Thank you!') ?><br>
                <?= Yii::$app->params['receipts']['receipt']['tagline'] ?></span>
            <?php endif ?>
        </p>
        <?php if($voided): ?>
            <p><strong>
                <?= Yii::t('app', 'Voided by Document #') ?> <?= Html::a($model->voidingReceipt()->completeSequentialNumber, $model->voidingReceipt()->url, ['style'=>'text-decoration: none']) ?><br>
                <?= Yii::t('app', 'Voiding Date') ?>: <?=\Yii::$app->formatter->asDateTime($model->voided_at, $dateFormat) ?>
            </strong></p>
        <?php endif ?>
        <?php if($voiding): ?>
            <p>
                <?= Yii::t('app', 'Voids Document #') ?> <?= Html::a($model->voidedReceipt()->completeSequentialNumber, $model->voidedReceipt()->url, ['style'=>'text-decoration: none']) ?><br>
            </p>
        <?php endif ?>

        <?php if($framed!=1): ?>
            <hr>

            <?php if($qrcode>0): ?>
            <?= $qr ?>
            <?php endif ?>
                        
        <?php endif ?>
        
    </footer>

</div>
    <div class="actions">
    <footer class="actions">
        <?php if($framed!=1): ?>
            <?php if (!Yii::$app->user->isGuest && $model->getWorkflowStatus()->getId()=='DigitalReceiptWorkflow/issued'): ?>
                <hr class="actions">
                <?php if ($model->getWorkflowStatus()->getId()=='DigitalReceiptWorkflow/issued'): ?>
                    <br><?= Html::a(Yii::t('app', 'Configure delivery'), ['/digital-receipts/update', 'id'=>$model->id], ['class'=>'action', 'target'=>'_top']) ?>
                <?php endif ?>
                <?= Html::a(Yii::t('app', 'Show Details'), ['/digital-receipts/view', 'id'=>$model->id], ['class'=>'action', 'target'=>'_top']) ?>
            <?php endif ?>
        <?php endif ?>
    </footer>
    </div>

</div>

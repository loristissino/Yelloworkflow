<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

$fontUrl = Url::to('@web/css/NotoSansMono-Regular.ttf', true);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <style>
/* Base Font Settings */

@font-face {
 font-family: 'Noto Sans Mono';
 src: url(<?=$fontUrl?>) format('truetype');
}

body {
    font-family: 'Noto Sans Mono', 'Courier New', Courier, monospace;
    background-color: white;
    color: #333;
}

.receipt-voided::before {
  content: "<?= strtoupper(Yii::t('app', 'Voided')) ?>";
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%) rotate(-45deg);
  font-size: 60px;
  font-weight: bold;
  color: rgba(255, 0, 0, 0.2);
  pointer-events: none;
  z-index: 1;
  white-space: nowrap;
}

.receipt-voiding::before {
  content: "<?= strtoupper(Yii::t('app', 'Voiding')) ?>";
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%) rotate(-45deg);
  font-size: 60px;
  font-weight: bold;
  color: rgba(127, 63, 0, 0.2);
  pointer-events: none;
  z-index: 1;
  white-space: nowrap;
}


.return-receipt::before {
  content: "<?= strtoupper(Yii::t('app', 'Return')) ?>";
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%) rotate(-45deg);
  font-size: 60px;
  font-weight: bold;
  color: rgba(0, 127, 255, 0.4);
  pointer-events: none;
  z-index: 1;
  white-space: nowrap;
}


/* The Receipt "Paper" */
.receipt-container {
    max-width: 420px; 
    width: 60%;
    margin: 20px auto;
    padding: 16px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); /* Paper shadow effect */
    border-top: 1px solid #ddd; /* Subtle border */
}

.receipt-container.framed {
    width: 100%;
}

/* Typography & Layout */
.receipt-header {
    text-align: center;
    margin-bottom: 15px;
}

.logo {
    width: 100%;
    height: auto;
    text-align: center;
}

img.logo {
    width: 50%;
}

.company-name {
    font-size: 0.7rem;
    font-weight: bold;
    margin: 0 0 5px 0;
    text-transform: uppercase;
}

.company-address, .company-identification {
    font-size: 0.5rem;
    color: #555;
}

/* Dashed Separator */
.dashed-line {
    border: none;
    border-top: 1px dashed #333;
    margin: 10px 0;
}

/* Items Table */
.receipt-items {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.6rem;
}

.vat-codes {
    font-size: 0.6rem;
}

.receipt-items th {
    text-align: left;
    border-bottom: 1px solid #333;
    padding-bottom: 5px;
    font-size: 0.6rem;
    text-transform: uppercase;
}

.receipt-items th.col-vat {
    text-align: center;
}

.receipt-items td {
    padding: 8px 0;
    vertical-align: top;
}

.receipt-items td.amount {
    padding: 8px 0;
    vertical-align: bottom;
}

.notes {
    font-style: italic;
    font-size: 0.5rem;
    color: #9e042d;
}

.discount, .vat-codes {
    font-style: italic;
    font-size: 0.50rem;
    color: #9e042d;
}


/* Columns */
.col-desc { width: 70%; padding-right: 10px; }
.col-amount, th.col-amount { width: 10%; text-align: right; vertical-align: bottom}
.col-vat { width: 20%; text-align: center; }

.col-qty span {margin-right: 10px;}

.unit-price-sub, .issuer {
    color: #9e042d;
    font-weight: bold;
}

/* Totals Section */
.receipt-totals {
    margin: 15px 0;
}

.total-row {
    margin-bottom: 5px;
}

.big-total {
    font-size: 1rem;
    font-weight: bold;
}

.payment-info {
    font-size: 0.8rem;
    font-style: italic;
}

.action {
    font-size: 1.5rem;
    margin: 20px;
}

/* Footer Meta */
.receipt-meta {
    text-align: center;
    font-size: 0.6rem;
    color: #777;
    margin-top: 15px;
}

.thank-you {
    margin-top: 10px;
    font-weight: bold;
}

.qrcode {
    width: 100%;
    max-width: 220px;
    height: auto;
    display: block;
    margin: 10px auto 0;
}

a.action {
    display: block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 1.2em;
}

a.action:hover {
    background-color: #0056b3;
}

div.actions {
    max-width: 460px; 
    text-align: center;
    margin: 0 auto;
}

footer.actions {
    background-color: white;
    padding: 0px 20px;
}

hr.actions {
    border: none;
    height: 2px;
    background-color: #9e042d;  /* Using your brand color */
    /*margin: 0px 40px 20px 20px;*/
}

/* === RESPONSIVE: Mobile === */
@media (max-width: 480px) {
    .receipt-container {
        width: 92%;
        max-width: 420px;
        margin: 0;
        box-shadow: none; /* Flat on mobile */
    }
}

/* === PRINT / PDF === */
/* This ensures that when they Print > Save to PDF, it looks perfect */
@media print {
    
    body {
        background: none;
        -webkit-print-color-adjust: exact;
    }
    .receipt-container {
        width: 100%; /* Use full paper width (or set to 80mm if using a thermal printer) */
        max-width: none;
        margin: 0;
        padding: 20px;
        box-shadow: none;
        border: none;
        box-sizing: border-box; /* Ensures padding doesn't push width past 100% */
    }
    
    /* Prevent table rows from breaking across pages */
    table tr {
        page-break-inside: avoid !important;
    }

    /* Additional safety for cell content */
    table td {
        page-break-inside: avoid !important;
    }
    
    /* Force the cell containing the description and notes to stay together */
    td.col-desc {
        page-break-inside: avoid !important;
    }

    /* Ensure the notes div doesn't start a new page break context */
    .notes {
        page-break-inside: avoid !important;
        display: inline-block; width: 100%;
    }
    
    /* Hide non-essential UI elements if you had a navbar/footer in your layout */
    .navbar, .footer, .breadcrumb {
        display: none !important; 
    }
}
</style>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="container">
        <?= $content ?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

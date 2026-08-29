<?php

use yii\helpers\Html;
use yii\helpers\Url;

$maxAmount = 1000;

$digitalReceiptJsConfig = [
    'maxAmount' => (float) $maxAmount,
    'messages' => [
        'fillAllFields' => Yii::t('app', 'Please fill all fields'),
        'priceCannotBeZero' => Yii::t('app', 'The price / amount cannot be zero.'),
        'discountTooLarge' => Yii::t('app', 'The discount cannot exceed the amount of'),
        'fillNotes' => Yii::t('app', 'Please fill the notes field'),
        'cameraError' => Yii::t('app', 'Camera error:'),
        'catalogUpdated' => Yii::t('app', 'Catalog updated: {number} products.'),
        'goOnlineToLogin' => Yii::t('app', 'You must to be online to login.'),
        'mustBeLoggedIn' => Yii::t('app', 'You must be logged in (be sure to check the "Remember me" box) to use this app.'),
        'invalidEmail' => Yii::t('app', 'Invalid Email.'),
        'invalidPhone' => Yii::t('app', 'Invalid Phone.'),
    ],
    'urlTemplates' => [
        'onlineReceipt' => Url::toRoute(['site/digital-receipt', 'id'=>'client_id', 'code'=>'hash', 'format'=>'html'], true),
    ],
];

$this->registerJs(
    'window.digitalReceiptConfig = ' .
    \yii\helpers\Json::htmlEncode($digitalReceiptJsConfig) . ';',
    \yii\web\View::POS_HEAD,
    'digital-receipt-config'
);

?>
<template id="types-page">
    <h2><?= Yii::t('app', 'Select receipt type') ?></h2>
    <ul id="typeList"></ul>
    <hr>
    <button class="btn btn-info" id="list"><?= Yii::t('app', 'List Receipts I Issued') ?></button><br>
    <button class="btn btn-info" id="update-catalog"><?= Yii::t('app', 'Refresh Catalog') ?></button><br>
</template>

<template id="receipt-page">
    <h2 id="receipt-type-description-in-use"><?= Yii::t('app', 'Digital Receipt') ?></h2>
    <table class="table">
        <thead>
            <tr>
                <th><?=Yii::t('app', 'Description') ?></th>
                <th class="number"><?=Yii::t('app', 'Qty') ?></th>
                <th class="number"><?=Yii::t('app', 'Price') ?></th>
                <th class="number"><?=Yii::t('app', 'Discount') ?></th>
                <th class="amount"><?=Yii::t('app', 'Amount') ?></th>
                <th class="buttons"></th>
            </tr>
        </thead>
        <tbody id="receipt-rows">
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-right"><?=Yii::t('app', 'Grand Total') ?></th>
            <th class="amount"><span id="grand-total">0.00</span></th>
            <th class="buttons"></th>
        </tr>
        </tfoot>
    </table>
    
    <h2 id="receipt-type"></h2>
        <button type="button"
                id="addItemBtn"
                data-toggle="modal"
                class="btn btn-primary"
                data-target="#addItemModal"
        >
            <?=Yii::t('app', 'Add Item') ?>
        </button>
        <button type="button"
                id="scanISBNButton"
                data-toggle="modal"
                class="btn btn-primary"
                data-target="#scanISBNModal"
        >
            <?=Yii::t('app', 'Scan ISBN') ?>
        </button>


    <?php /*<ul id="itemList"></ul> */?>
    
    <div class="form-group field-total_amount">
        <label class="control-label" for="total_amount"><?= Yii::t('app', 'Total Amount') ?></label>
        <input type="text" id="total_amount" class="form-control" name="DigitalReceipt[total_amount]" readonly>
    </div>    
    
    <div class="form-group field-cash_payment_amount">
        <label class="control-label" for="cash_payment_amount"><?= Yii::t('app', 'Cash Payment Amount') ?></label>
        <input type="text" id="cash_payment_amount" class="form-control" name="DigitalReceipt[cash_payment_amount]" readonly>
        <div class="hint-block"><?= Yii::t('app', 'Use the buttons/icons below to select the payment method') ?></div>
    </div>
    
    <div class="form-group field-electronic_payment_amount">
        <label class="control-label" for="electronic_payment_amount"><?= Yii::t('app', 'Electronic Payment Amount') ?></label>
        <input type="text" id="electronic_payment_amount" class="form-control" name="DigitalReceipt[electronic_payment_amount]" readonly>
        <div class="hint-block"><?= Yii::t('app', 'Use the buttons/icons below to select the payment method') ?></div>
    </div>
    
    <div>
        <span class="payment-selector emoji-button" id="payment-selector-cash" title="<?=Yii::t('app', 'Payment by cash') ?>">💶</span>
        <span class="payment-selector emoji-button" id="payment-selector-card" title="<?=Yii::t('app', 'Payment by card') ?>">💳</span>
    </div>
    
    <hr>
    
    <div class="form-group field-digitalreceipt-email">
        <label class="control-label" for="digitalreceipt-email"><?=Yii::t('app', 'Email') ?></label>
        <input type="email" id="digitalreceipt-email" class="form-control" name="DigitalReceipt[email]">
        <div class="hint-block" id="receipt-email-hint"><?= Yii::t('app', 'If this field is left empty, the receipt will be sent to {email}') ?></div>
    </div>
    <div class="form-group field-digitalreceipt-phone">
        <label class="control-label" for="digitalreceipt-phone">Telefono</label>
        <input type="text" id="digitalreceipt-phone" class="form-control" name="DigitalReceipt[phone]">
    </div>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Issue'), ['id'=>'issueButton', 'class' => 'btn btn-success loader', 'disabled'=>true]) ?>
        <?= Html::tag('div', Yii::t('app', 'For this kind of receipts, the amount cannot exceed {amount}.'), ['class'=>'hint-block', 'id'=>'receipt-type-max_amount']) ?>
    </div>
    
    <button id="backBtn" class="btn btn-info"><?= Yii::t('app', 'Cancel') ?></button>
    
    <div class="modal fade hidden" id="addItemModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button id="addItem-closeBtn" type="button" class="close" data-dismiss="modal">&times;</button>
            <?php /*<h4><?=Yii::t('app', 'Add Item') ?></h4> */ ?>
            <?php /*<?php //= Html::encode($digitalReceiptType->description) ?></p> */ ?>
            <p id="receipt-type-description"></p>
          </div>

          <div class="modal-body">

            <div class="form-group">
                <div class="form-group" id="description-field">
                    <label><?=Yii::t('app', 'Description') ?></label>
                          <select id="item-description">
          </select>
                    <?php //= Product::getDropdownForModalForm('input', ['name'=>'item-description', 'id'=>'item-description', 'list'=>'products', 'class'=>'form-control']) ?>
                    <?php //= Product::getDropdownForModalForm($organizationalUnit, 'select', ['name'=>'item-description', 'id'=>'item-description', 'class'=>'form-control']) ?>
                    <input type="hidden" id="item-product-id">
                    <input type="hidden" id="item-label">
                </div>
            </div>

            <div class="form-group">
                <label><?=Yii::t('app', 'Quantity') ?></label>
                <input type="number" id="item-qty" class="form-control" value="1">
            </div>

            <div class="form-group">
                <label><?=Yii::t('app', 'Price') ?></label>
                <span class="discount"><?=Yii::t('app', 'Minimum Price') ?>: <span id="min-price"></span><span id="max-discount"></span>
                    
                    — <?=Yii::t('app', 'Standard Discount') ?>: <span id="standard-discount"></span>
                    <span id="apply-discount" class="discount-button" title="<?= Yii::t('app', 'Apply') ?>">⬇️</span>  
                    <span id="remove-discount" class="discount-button" title="<?= Yii::t('app', 'Remove') ?>">⬆️</span></span>
                <input type="number" step="0.01" id="item-price" class="form-control">
            </div>

            <div class="form-group">
                <label><?=Yii::t('app', 'Notes') ?></label>
                <input type="text" id="notes" class="form-control" value="">
            </div>

          </div>

          <div class="modal-footer">
            <div class="form-group">
                <?=Yii::t('app', 'Row Total') ?>: <strong><span id="preview-total">0.00</span></strong>
            </div>
            <button type="button" id="btn-add-item" class="btn btn-primary">
                <?=Yii::t('app', 'Add to Receipt') ?>
            </button>
          </div>

        </div>
      </div>
    </div>
    
    <div class="modal fade hidden" id="scanISBNModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button id="scanISBN-closeBtn" type="button" class="close" data-dismiss="modal">&times;</button>
            <h2><?=Yii::t('app', 'Scan ISBN') ?></h2>
          </div>

          <div class="modal-body">
            <div id="scanner-container"></div>
            <div class="result-box">
                    <strong><?= Yii::t('app', 'Scanned ISBN:') ?></strong> <span id="scanned-result"><?= Yii::t('app', 'Waiting for scan...') ?></span>
                    <div id="info-isbn-not-found">
                        <?= Yii::t('app', 'ISBN code not found in the database.') ?>
                    </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" id="btn-add-book-by-isbn" class="btn btn-primary" disabled>
                <?=Yii::t('app', 'Add Item') ?>
            </button>
          </div>

        </div>
      </div>
    </div>
    
</template>

<template id="receipts-list">
      <h2><?= Yii::t('app', 'Receipts I issued') ?></h2>
      <button id="newReceiptBtn" class="btn btn-success"><?= Yii::t('app', 'New Receipt') ?></button>
      <ul id="receiptList"></ul>
      <button id="syncBtn" class="btn btn-primary"><?=Yii::t('app', 'Sync now') ?> (<span id="outOfSyncNo"></span>)</button>
      <button id="notificationsBtn" class="btn btn-primary hidden"><?=Yii::t('app', 'Enable notifications') ?></button>
      <p id="notifications-disabled-message" class="hidden"><?= Yii::t('app', 'You have not enabled the notifications. If you want to be kept updated where receipts are successfully uploaded to the server, do it now from the app\'s settings.') ?></p>
      <p id="notifications-enabled-message" class="hidden"><?= Yii::t('app', 'Notifications are enabled.') ?></p>
      <hr>
      <button id="logsBtn" class="btn btn-info"><?=Yii::t('app', 'Logs') ?></button>
</template>

<template id="qrcode-page">
      <h2><?= Yii::t('app', 'Digital Receipt') ?></h2>
      <p><?= Yii::t('app', 'Sorry, we are offline. The receipt will be available here as soon as we get back online.') ?></p>
      <div id="qrcode"></div>
      <div id="receipt_url"></div>
      <p><?= Yii::t('app', 'Total Amount') ?>: € <span id='qrcode-page-amount'></span></p>
      <ul id="receipt-items"></ul>
      <hr>
      <button id="homeBtn" class="btn btn-info"><?= Yii::t('app', 'List Receipts I Issued') ?></button>
      
</template>

<template id="logs-page">
      <h2><?= Yii::t('app', 'Last Activities')?></h2>
      <div id="logs"></div>
      <button id="clearLogsBtn" class="btn btn-secondary"><?= Yii::t('app', 'Clear Logs') ?></button>
      <hr>
      <button id="homeBtn" class="btn btn-info"><?= Yii::t('app', 'List Receipts I Issued') ?></button>
</template>

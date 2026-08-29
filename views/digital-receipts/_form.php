<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;
use app\models\Product;



/* @var $this yii\web\View */
/* @var $model app\models\DigitalReceipt */
/* @var $form yii\widgets\ActiveForm */

$searchUrl = Url::to(['digital-receipts/product-search']);

$maxAmount = $digitalReceiptType->amount_hard_limit;

$digitalReceiptJsConfig = [
    'maxAmount' => (float) $maxAmount,
    'messages' => [
        'fillAllFields' => Yii::t('app', 'Please fill all fields'),
        'priceCannotBeZero' => Yii::t('app', 'The price / amount cannot be zero.'),
        'discountTooLarge' => Yii::t('app', 'The discount cannot exceed the amount of'),
        'fillNotes' => Yii::t('app', 'Please fill the notes field'),
        'cameraError' => Yii::t('app', 'Camera error:'),
    ],
];

$this->registerJs(
    'window.digitalReceiptConfig = ' .
    \yii\helpers\Json::htmlEncode($digitalReceiptJsConfig) . ';',
    \yii\web\View::POS_HEAD,
    'digital-receipt-config'
);

$this->registerJsFile(
    '@web/js/digital-receipt.js',
    [
        'position'=>\yii\web\View::POS_END
    ],
    'digital-receipt-js'
);

$this->registerCssFile(
    '@web/css/digital_receipt_form.css',
    [],
    'digital-receipt'
);

?>

<div class="digital-receipt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord): ?>
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
    <?php endif ?>

    <?php if($model->isNewRecord): ?>
        <?php 
            $uniqId = uniqid(); 
        ?>
        <button type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#addItemModal">
            <?=Yii::t('app', 'Add Item') ?>
        </button>
        <button type="button"
                id="scanISBNButton"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#scanISBNModal">
            <?=Yii::t('app', 'Scan ISBN') ?>
        </button>
    <?php endif ?>

    <hr>

    <?= $form->field($model, 'total_amount')->textInput([
            'id' => 'total_amount', 
            'readonly' => true
        ]) ?>
    
    <?php if($model->isNewRecord): ?>

    <?= $form->field($model, 'cash_payment_amount')->textInput([
            'id' => 'cash_payment_amount',
            'readonly' => true 
        ])->hint(Yii::t('app', 'Use the buttons/icons below to select the payment method')) ?>

    <?php //= $form->field($model, 'tags')->textInput() ?>

    <?= $form->field($model, 'electronic_payment_amount')->textInput([
            'id' => 'electronic_payment_amount',
            'readonly' => true 
        ])->hint(Yii::t('app', 'Use the buttons/icons below to select the payment method')) ?>

    <div style="font-size: 4em">
        <span class="payment-selector emoji-button" id="payment-selector-cash" title="<?=Yii::t('app', 'Payment by cash') ?>">💶</span>
        <span class="payment-selector emoji-button" id="payment-selector-card" title="<?=Yii::t('app', 'Payment by card') ?>">💳</span>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Issue'), ['id'=>'issueButton', 'class' => 'btn btn-success loader', 'disabled'=>true]) ?>
        <img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />
        <?= Html::tag('div', Yii::t('app', 'For this kind of receipts, the amount cannot exceed {amount}.', ['amount'=> Yii::$app->formatter->asCurrency($digitalReceiptType->amount_hard_limit)]), ['class'=>'hint-block']) ?>
    </div>
    
    <?php endif ?>

    <hr>
    
    <?php if($model->getWorkflowStatus() && $model->getWorkflowStatus()->getId() == 'DigitalReceiptWorkflow/issued'): ?>
        <?= $form->field($model, 'email')->textInput(['type' => 'email'])->hint(Yii::t('app', 'If this field is left empty, the receipt will be sent to {email}', ['email'=>$model->organizationalUnit->email] )) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send'), ['id'=>'sendButton', 'class' => 'btn btn-success loader']) ?>
            <img style="display: none" id="loader" src="<?= Url::to('@web/images/submit_loader.gif') ?>" />
        </div>
    <?php endif ?>

    <?php //= $form->field($model, 'sent_at')->textInput() ?>

    <?php //= $form->field($model, 'transaction_id')->textInput() ?>

    <?= $form->field($model, 'client_id')->textInput([
        'maxlength' => true,
        'id' => 'client-uuid', // <--- Add this ID
        'readonly' => true     // <--- Prevent manual editing
    ]) ?>

    <?php /*
    <?= $form->field($model, 'assigned_id')->textInput(['maxlength' => true, 'readonly'=>true]) ?>

    <?= $form->field($model, 'document_number')->textInput(['maxlength' => true, 'readonly'=>true]) ?>

    <?= $form->field($model, 'api_response')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'issuer_data')->textInput(['readonly'=>true]) ?>

    <hr>
    */ ?>
        
    <?= $form->errorSummary($model) ?>


    <?php ActiveForm::end(); ?>

</div>

<div class="modal fade" id="addItemModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4><?=Yii::t('app', 'Add Item') ?></h4>
        <p><?= Html::encode($digitalReceiptType->description) ?></p>
      </div>

      <div class="modal-body">

        <div class="form-group">
            <div class="form-group" id="description-field">
                <label><?=Yii::t('app', 'Description') ?></label>
                <?php //= Product::getDropdownForModalForm('input', ['name'=>'item-description', 'id'=>'item-description', 'list'=>'products', 'class'=>'form-control']) ?>
                <?= Product::getDropdownForModalForm($organizationalUnit, 'select', ['name'=>'item-description', 'id'=>'item-description', 'class'=>'form-control']) ?>
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

<div class="modal fade" id="scanISBNModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4><?=Yii::t('app', 'Scan ISBN') ?></h4>
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

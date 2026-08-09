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

$script = "

let itemIndex = 0;

$('#btn-add-item').on('click', function() {

    let description  = $('#item-description').val();
    let qty   = parseFloat($('#item-qty').val());
    let price = parseFloat($('#item-price').val()).toFixed(2);
    let originalPrice = parseFloat($('#item-price').attr('data-original_price')).toFixed(2);
    let standardDiscount = parseFloat($('#standard-discount').attr('data-standard-discount')).toFixed(2);
    let maxDiscount = parseFloat($('#max-discount').attr('data-max-discount')).toFixed(2);
    let discount = ((originalPrice - price)*qty).toFixed(2);
    
    if (originalPrice == 0) {
        discount = 0;
        originalPrice = price;
    }
    
    let shownDiscount = discount;
    if (discount<0) {
        shownDiscount = '';
    }
    let productId = parseInt($('#item-product-id').val());
    let notes = $('#notes').val();
    let escapedNotes = notes.replace(/'/g, '&#39;');
    let label = $('#item-label').val();

    if (!description || !qty || !price) {
        alert('" . Yii::t('app', 'Please fill all fields') . "');
        return;
    }

    if (price <= 0  || Number.isNaN(price)) {
        alert('" . Yii::t('app', 'The price / amount cannot be zero.') . "');
        return;
    }
    
    if ($('#item-description').attr('data-isbn')){
        if(price < originalPrice - maxDiscount) {
            alert('" . Yii::t('app', 'The discount cannot exceed the amount of') . " ' + maxDiscount);
            return;
        }
    }
    
    if (!$('#notes').val() && $('#item-description').attr('data-extra_info_required')) {
        $('#notes').css('background-color', '#FFC0CB');
        alert('" . Yii::t('app', 'Please fill the notes field') . "');
        return;
    }
    
    $('#notes').attr('placeholder', '').val('');

    let total = (qty * price).toFixed(2);

    let row = `
    <tr>
        <td>
            \${label}<br>
            <span class='notes'>\${notes}</span>
            <input type='hidden' name='DigitalReceiptLine[\${itemIndex}][description]' value='\${label}'>
            <input type='hidden' name='DigitalReceiptLine[\${itemIndex}][notes]' value='\${escapedNotes}'>
            <input type='hidden' name='DigitalReceiptLine[\${itemIndex}][product_id]' value='\${productId}'>
        </td>
        <td class='number'>
            \${qty}
            <input type='hidden' name='DigitalReceiptLine[\${itemIndex}][quantity]' value='\${qty}'>
        </td>
        <td class='number price-cell'>
            <span class='price'>\${originalPrice}</span>
            <input type='hidden' name='DigitalReceiptLine[\${itemIndex}][unit_price]' value='\${originalPrice}'>
        </td>
        <td class='number'>
            \${shownDiscount}
            <input type='hidden' name='DigitalReceiptLine[\${itemIndex}][discount]' value='\${discount}'>
        </td>
        <td class='row-total amount number'>\${total}</td>
        <td class='buttons'>
            <button type='button' class='btn btn-sm btn-danger btn-remove'>X</button>
        </td>
    </tr>
    `;

    $('#receipt-rows').append(row);
    
    compactReceiptTable();
    reindexReceiptTable();
    
    calculateGrandTotal();

    itemIndex++;

    $('#addItemModal').modal('hide');

    $('#item-description').val('');
    $('#item-qty').val(1);
    $('#item-price').val('');
    $('#notes').css('background-color','');

});

function updateRowPreviewTotal() {

    let qty   = parseFloat($('#item-qty').val()) || 0;
    let price = parseFloat($('#item-price').val()) || 0;

    let originalPrice = parseFloat($('#item-price').attr('data-original_price'));
    let maxDiscount = parseFloat($('#max-discount').attr('data-max-discount'));

    $('#btn-add-item').prop('disabled', false);

    $('#item-price').css('background-color', '');
    if ($('#item-description').attr('data-isbn')){
        if(price < originalPrice - maxDiscount) {
            $('#item-price').css('background-color', '#FFC0CB');
            $('#btn-add-item').prop('disabled', true);
        }
    }
    if(originalPrice > 0 && price > originalPrice) {
        $('#item-price').css('background-color', '#FFC0CB');
        $('#btn-add-item').prop('disabled', true);
    }

    let total = (qty * price).toFixed(2);
    console.log(total);

    $('#preview-total').text(total);
}

$('#item-qty, #item-price').on('input', updateRowPreviewTotal);

$(document).on('click', '.btn-remove', function() {
    $(this).closest('tr').remove();
    calculateGrandTotal();

});

function calculateGrandTotal() {

    let grandTotal = 0;

    $('#receipt-rows tr').each(function() {

        let rowTotal = parseFloat($(this).find('.row-total').text()) || 0;
        grandTotal += rowTotal;

    });

    $('#grand-total').text(grandTotal.toFixed(2));
    $('#total_amount').val(grandTotal.toFixed(2));
    $('#cash_payment_amount').val(null);
    $('#electronic_payment_amount').val(null);
    
    fixIssueButton();
}

$('#apply-discount').on('click', function(){
    let p = ($('#item-price').attr('data-original_price') - $('#standard-discount').attr('data-standard-discount')).toFixed(2);
    $('#item-price').val(p);
    updateRowPreviewTotal();
});

$('#remove-discount').on('click', function(){
    $('#item-price').val($('#item-price').attr('data-original_price'));
    updateRowPreviewTotal();
});

let selectedOption = null;


$('#item-description').on('change', function() {
    let id = $('#item-description-select').val();
    console.log('selected');
    
    selectedOption = $('#item-description').find('option:selected');
    
    if (selectedOption.length) {
        // Access the selected option's attributes
        const id = selectedOption.data('id');
        const description = selectedOption.data('description');
        const originalPrice = selectedOption.data('original_price');
        const isbn = selectedOption.data('isbn');
        const maxDiscount = selectedOption.data('max-discount');
        const minPrice = (originalPrice - maxDiscount).toFixed(2);
        const standardDiscount = selectedOption.data('standard_discount');
        const extraInfoRequired = selectedOption.data('extra_info_required');

        console.log('Selected Item:', {
          id: id,
          originalPrice: originalPrice,
          isbn: isbn,
          standardDiscount: standardDiscount,
          extraInfoRequired: extraInfoRequired
        });

        // Populate the hidden input with the product ID
        $('#item-product-id').val(id);
        $('#item-label').val(description);
        $('#standard-discount').html(standardDiscount).attr('data-standard-discount', standardDiscount);
        $('#max-discount').html(maxDiscount).attr('data-max-discount', maxDiscount);
        $('#min-price').html(minPrice);
        $('#item-price').val(originalPrice).attr('data-original_price', originalPrice);
        $('#item-description')
            .attr('data-isbn', isbn)
            .attr('data-extra_info_required', extraInfoRequired)
        ;
        $('#notes').attr('placeholder', extraInfoRequired);
        if (isbn!='' || maxDiscount>0) {
            $('.discount').show();
        }
        else {
            $('.discount').hide();
        }
        $('#item-qty').focus().select();
        updateRowPreviewTotal();
    }

});

$('#payment-selector-cash').on('click', function() {
    $('#cash_payment_amount').val($('#total_amount').val());
    $('#electronic_payment_amount').val(null);
    fixIssueButton();
});

$('#payment-selector-card').on('click', function() {
    $('#cash_payment_amount').val(null);
    $('#electronic_payment_amount').val($('#total_amount').val());
    fixIssueButton();
});

function fixIssueButton() {
    let grandTotal = $('#total_amount').val();
    $('#issueButton').prop('disabled', ($('#cash_payment_amount').val() + $('#electronic_payment_amount').val() != grandTotal) || (grandTotal > $maxAmount || grandTotal <= 0));
}

function compactList(items) {
  const result = [];

  items.forEach(item => {
    const existingIndex = result.findIndex(existing =>
      existing.productId === item.productId &&
      existing.unitPrice === item.unitPrice &&
      existing.notes === item.notes
    );

    if (existingIndex !== -1) {
      // Found a duplicate, add quantity to existing item
      result[existingIndex].quantity += item.quantity;
    } else {
      // New item, add it to result
      result.push({ ...item });
    }
  });

  return result;
};


function compactReceiptTable() {
  const rows = document.querySelectorAll('#receipt-rows tr');
  const seenItems = new Map();

  rows.forEach(row => {
    // Select inputs using CSS attribute selectors that end with the specific keys
    const productIdInput = row.querySelector('input[name$=\"[product_id]\"]');
    const unitPriceInput = row.querySelector('input[name$=\"[unit_price]\"]');
    const notesInput = row.querySelector('input[name$=\"[notes]\"]');
    const quantityInput = row.querySelector('input[name$=\"[quantity]\"]');
    const discountInput = row.querySelector('input[name$=\"[discount]\"]');
    const rowTotalTd = row.querySelector('.row-total');

    // Skip if row doesn't contain the expected inputs
    if (!productIdInput || !unitPriceInput || !notesInput || !quantityInput) return;

    // Extract values
    const productId = productIdInput.value;
    const unitPrice = parseFloat(unitPriceInput.value);
    const notes = notesInput.value;
    const quantity = parseFloat(quantityInput.value);
    const discount = parseFloat(discountInput.value) || 0.00;
    const rowTotal = parseFloat(rowTotalTd.textContent) || 0.00;
    const netUnitPrice = (unitPrice - discount / quantity).toFixed(2);

    // Create a unique key for matching exactly like your array example
    const key = `\${productId}_\${netUnitPrice}_\${notes}`;

    if (seenItems.has(key)) {
      // -- DUPLICATE FOUND: Update the existing row --
      const existing = seenItems.get(key);

      // 1. Add quantities together
      existing.quantity += quantity;
      existing.quantityInput.value = existing.quantity;
      updateTextNode(existing.quantityInput.parentElement, existing.quantity);

      // 2. Add discounts together
      existing.discount += discount;
      existing.discountInput.value = existing.discount.toFixed(2);
      updateTextNode(existing.discountInput.parentElement, existing.discount.toFixed(2));

      // 3. Add row totals together
      existing.total += rowTotal;
      existing.rowTotalTd.textContent = existing.total.toFixed(2);

      // 4. Remove this duplicate row from the DOM
      row.remove();

    } else {
      // -- NEW ITEM: Store its references in the Map --
      seenItems.set(key, {
        row: row,
        quantity: quantity,
        quantityInput: quantityInput,
        discount: discount,
        discountInput: discountInput,
        total: rowTotal,
        rowTotalTd: rowTotalTd
      });
    }
    console.table(seenItems);
  });

  /**
   * Helper function to update the visible text inside a <td> 
   * without destroying the hidden <input> elements inside it.
   */
  function updateTextNode(tdElement, newValue) {
    // Remove existing text nodes (the visible numbers)
    Array.from(tdElement.childNodes).forEach(node => {
      if (node.nodeType === Node.TEXT_NODE) {
        node.remove();
      }
    });
    // Prepend the new value so it appears before the hidden inputs
    tdElement.prepend(document.createTextNode(newValue + ''));
  }
}

function reindexReceiptTable() {
  // Grab all the rows that are currently left in the table
  const rows = document.querySelectorAll('#receipt-rows tr');

  rows.forEach((row, index) => {
    // Array indexes start at 0, but your HTML format starts at 1
    const newIndex = index + 1;

    // Find all inputs in this specific row
    const inputs = row.querySelectorAll('input');

    inputs.forEach(input => {
      if (input.name) {
        // This regular expression /\[\d+\]/ finds the first instance of 
        // a number inside square brackets (e.g., [3]) and replaces it 
        // with the new sequential index (e.g., [1]).
        input.name = input.name.replace(/\[\d+\]/, `[\${newIndex}]`);
      }
    });
  });
}


$('.discount').hide();
$('#item-description').val('');
 
";
$this->registerJs($script);


$this->registerJs(
    "
    
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('addItemModal');
        
        if (modal) {
            // Bootstrap 3 uses jQuery events
            $(modal).on('shown.bs.modal', function () {
                console.log('Modal shown event fired');
                document.getElementById('item-description').focus();
            });
        }
    });
    
    $('#client-uuid').val(self.crypto.randomUUID());
    
    $('.loader').on('click', function(event){
        $('#loader').show();
    });
    ",
    \yii\web\View::POS_END,
    'loader_manager'
);

$this->registerJs(
    "
        $('#info-isbn-not-found').hide();
        const container = document.getElementById('scanner-container');
        const resultSpan = document.getElementById('scanned-result');
    
        function onScanSuccess(isbn) {
            resultSpan.innerText = isbn;
            console.log(isbn);
            resultSpan.innerText = isbn;
            document.getElementById('btn-add-book-by-isbn').disabled = false;
        }
        
        document.getElementById('btn-add-book-by-isbn').addEventListener('click', function() {
            let scannedISBN = resultSpan.innerText;
            const selectedOption = $(`#item-description option[data-isbn='\${scannedISBN}']`);

            if (selectedOption.length) {
                // Set the select value to the matching option
                $('#item-description').val(selectedOption.val());
                $('#item-description').trigger('change');
                
                // Switch modals
                $('#addItemModal').modal('show');
                resultSpan.innerText = '';
                $('#info-isbn-not-found').hide();
                $('#scanISBNModal').modal('hide');
            } else {
                // ISBN not found - handle this case
                $('#info-isbn-not-found').show();
            }
        });

        if ('BarcodeDetector' in window) {
            
            // Create the video element dynamically
            const video = document.createElement('video');
            video.id = 'native-video';
            video.autoplay = true;
            video.playsInline = true; 
            container.appendChild(video);

            const barcodeDetector = new BarcodeDetector({ formats: ['ean_13'] });

            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => {
                    video.srcObject = stream;
                    video.addEventListener('play', () => detectBarcode(video, barcodeDetector));
                })
                .catch(err => { badge.innerText = \"Camera error: \" + err.message; });

            async function detectBarcode(videoElement, detector) {
                try {
                    const barcodes = await detector.detect(videoElement);
                    if (barcodes.length > 0) {
                        onScanSuccess(barcodes[0].rawValue);
                    }
                } catch (e) {
                    console.error(\"Native detection error:\", e);
                }
                // Loop the detection
                requestAnimationFrame(() => detectBarcode(videoElement, detector));
            }

        }
        else {
            $('#scanISBNButton').hide();
        }
        
    ",
    \yii\web\View::POS_END,
    'scanner_manager'
);


$this->registerCss(
    "
    .discount{
        color: #1E90FF;
        font-size: 0.8em;
    }

    .discount-button, .payment-selector{
        cursor: pointer;
    }
    
    .number {
        text-align: right;
    }

    span.notes {
        color: #1E90FF;
        font-size: 0.8em;
    }
    
    #max-discount {
        display: none;
    } 

  .emoji-button {
    font-size: 2em;
    cursor: pointer;
    user-select: none;
    transition: opacity 0.2s;
  }

  .emoji-button:hover:not(.disabled) {
    opacity: 0.8;
  }

  .emoji-button.disabled {
    opacity: 0.5;
    filter: grayscale(100%);
    cursor: not-allowed;
    pointer-events: none;
  }

    table {
        table-layout: fixed;
        width: 100%;
    }

    button.btn-remove {
        margin: 0px;
        padding: 0px;
    }
    
    .buttons {
        width: 16px;
    }
    
    #native-video {
        width: 100%;
        height; auto;
    }
    
    #info-isbn-not-found {
        background-color: orange;
    }
    
    ",
);

\yii\jui\JuiAsset::register($this);




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

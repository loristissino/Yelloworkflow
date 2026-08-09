<?php

use yii\helpers\Html;

use app\assets\HtmxAsset;

HtmxAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\DigitalReceiptLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\DigitalReceiptLine */
/* @var $index string */

$autofocusSearch = $autofocusSearch ?? false; 
$autofocusQty = $autofocusQty ?? false;

?>
<tr id="row-<?= $index ?>">
    <td>
        <?= Html::hiddenInput("DigitalReceiptLine[$index][product_id]", $model->product_id) ?>
        

        <div class="dropdown">
            <input type="search" class="form-control product-search" placeholder="Search product..."
                   name="search_term"
                   value="<?= $model->product ? $model->product->description : '' ?>" 
                   autocomplete="off"
                   
                   hx-get="<?= \yii\helpers\Url::to(['/products/search-product', 'index' => $index, 'digital_receipt_type_id'=>$type]) ?>"
                   
                   /* 1. BROADEN TRIGGER FOR MOBILE */
                   hx-trigger="input delay:300ms, keyup delay:300ms, search"
                   
                   hx-target="#search-results-<?= $index ?>"
                   data-results-id="search-results-<?= $index ?>"
                   
                   /* 2. PREVENT FORM SUBMIT ON 'GO' */
                   onkeydown="if(event.key === 'Enter') event.preventDefault()"
                   
                   <?= $autofocusSearch ? 'autofocus' : '' ?>
            >            
            <div id="search-results-<?= $index ?>" class="dropdown-menu" style="display:none;"></div>
        </div>    
    </td>

    <td>
        <input type="number" 
               name="DigitalReceiptLine[<?= $index ?>][quantity]" 
               value="<?= $model->quantity ?>"
               min="1" max="100"
               class="form-control"
               hx-post="<?= \yii\helpers\Url::to(['/digital-receipts/calculate-line', 'index' => $index]) ?>"
               hx-trigger="input delay:300ms, keyup delay:300ms"
               hx-target="#total-<?= $index ?>"
               hx-include="#row-<?= $index ?> input" 
               <?= $autofocusQty ? 'autofocus' : '' ?>
               onfocus="this.select()"
        >
    </td>

    <td>
        <input type="text" 
               name="DigitalReceiptLine[<?= $index ?>][unit_price]" 
               value="<?= $model->unit_price ?>"
               class="form-control"
               hx-post="<?= \yii\helpers\Url::to(['/digital-receipts/calculate-line', 'index' => $index]) ?>"
               hx-trigger="input delay:300ms, keyup delay:300ms"
               hx-target="#total-<?= $index ?>"
               hx-include="#row-<?= $index ?> input"
        >
    </td>

    <td id="total-<?= $index ?>">
        <?= number_format($model->quantity * $model->unit_price, 2) ?>
    </td>
</tr>

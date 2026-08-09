<?php
use yii\helpers\Html;

/* @var $products app\models\Product[] */
?>

<?php if (empty($products)): ?>
    <div class="list-group-item text-muted">
        <?= Yii::t('app', 'No products found.') ?>
    </div>
<?php else: ?>
    <?php foreach ($products as $product): ?>
        <div class="list-group-item list-group-item-action" 
             style="cursor: pointer;" 
             data-key="<?= $product->id ?>">
            
            <div class="d-flex w-100 justify-content-between">
                <strong class="mb-1"><?= Html::encode($product->description) ?></strong>
                <small class="text-muted pull-right"><?= number_format($product->unit_price, 2) ?> €</small>
            </div>
            
            <small class="text-muted">
                SKU: <?= Html::encode($product->sku) ?> 
                <?php if($product->standard_discount > 0): ?>
                    | Max Disc: <?= $product->standard_discount ?>%
                <?php endif; ?>
            </small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "digital_receipt_lines".
 *
 * @property int $id
 * @property int $digital_receipt_id
 * @property int $product_id
 * @property string|null $item_assigned_id
 * @property string|null $sku
 * @property string $description
 * @property float $unit_price
 * @property int $quantity
 * @property int $quantity_returned
 * @property float $discount
 * @property float $amount
 * @property string $vat_rate_code
 * @property string|null $notes
 * @property string $line_type // enum
 * @property float $unit_discount // computed
 *
 * @property DigitalReceipt $digitalReceipt
 * @property Product $product
 */
class DigitalReceiptLine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'digital_receipt_lines';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['digital_receipt_id', 'product_id', 'description', 'unit_price', 'amount', 'vat_rate_code'], 'required'],
            [['digital_receipt_id', 'product_id', 'quantity', 'quantity_returned'], 'integer'],
            [['unit_price', 'discount', 'amount'], 'number'],
            [['sku'], 'string', 'max' => 50],
            [['item_assigned_id'], 'string', 'max' => 10],
            [['description'], 'string', 'max' => 255],
            [['vat_rate_code'], 'string', 'max' => 5],
            [['notes'], 'string', 'max' => 500],
            [['line_type', 'signed_quantity', 'unit_discount'], 'safe'], // not from user's input
            [['digital_receipt_id'], 'exist', 'skipOnError' => true, 'targetClass' => DigitalReceipt::className(), 'targetAttribute' => ['digital_receipt_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            
            // CUSTOM VALIDATION for Discount
            ['discount', 'validateMaxDiscount'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'digital_receipt_id' => Yii::t('app', 'Digital Receipt'),
            'product_id' => Yii::t('app', 'Product ID'),
            'item_assigned_id' => Yii::t('app', 'Item Assigned'),
            'sku' => Yii::t('app', 'Sku'),
            'description' => Yii::t('app', 'Description'),
            'unit_price' => Yii::t('app', 'Unit Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'quantity_returned' => Yii::t('app', 'Quantity Returned'),
            'discount' => Yii::t('app', 'Discount'),
            'amount' => Yii::t('app', 'Amount'),
            'vat_rate_code' => Yii::t('app', 'Vat Rate Code'),
            'notes' => Yii::t('app', 'Notes'),
            'line_type' => Yii::t('app', 'Line Type'), // SALE, RETURN, VOIDING
            'signed_quantity' => Yii::t('app', 'Signed Quantity'), // computed by the DBMS
            'unit_discount' => Yii::t('app', 'Unit Discount'), // computed by the DBMS
        ];
    }

    public function validateMaxDiscount($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $product = Product::findOne($this->product_id);
            if ($product && $product->isbn) {
                $computedMaxDiscount = round($product->max_discount*$this->quantity,2);
                if ($this->discount > $computedMaxDiscount) {
                    $this->addError($attribute, Yii::t('app', 'Discount cannot exceed the maximum allowed: {max} -- {current}>{computed}', [
                        'max' => $product->max_discount,
                        'current' => $this->discount,
                        'computed' => $computedMaxDiscount,
                    ]));
                }
            }
        }
    }
    
    public function getFormattedAmount()
    {
        $amount = $this->amount * ($this->digitalReceipt->isReturnReceipt ? -1 : 1);
        return  Yii::$app->formatter->asCurrency($amount);
    }

    /**
     * Gets query for [[DigitalReceipt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceipt()
    {
        return $this->hasOne(DigitalReceipt::className(), ['id' => 'digital_receipt_id']);
    }

    /**
     * Gets query for [[Product]].s
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    
    public function cloneModel()
    {
        $model = new DigitalReceiptLine();
        foreach([
            'digital_receipt_id',
            'product_id',
            'sku',
            'description',
            'unit_price',
            'quantity',
            'discount',
            'item_assigned_id',
            'amount',
            'vat_rate_code',
        ] as $attribute) {
            $model->$attribute = $this->$attribute;
        }
        $model->save(false);
        return $model;
    }
    
    /**
     * {@inheritdoc}
     * @return DigitalReceiptLineQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DigitalReceiptLineQuery(get_called_class());
    }

}

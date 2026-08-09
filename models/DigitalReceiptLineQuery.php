<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DigitalReceipt]].
 *
 * @see DigitalReceiptLine
 */
class DigitalReceiptLineQuery extends \yii\db\ActiveQuery
{
    
    public function withSku($sku)
    {
        return $this->andWhere(['=', 'sku', $sku]);
    }

    public function withUnitPrice($price)
    {
        return $this->andWhere(['=', 'unit_price', $price]);
    }

    public function withQuantity($quantity)
    {
        return $this->andWhere(['=', 'quantity', $quantity]);
    }

    public function withDiscount($discount)
    {
        return $this->andWhere(['=', 'discount', $discount]);
    }
    
    public function withLineAttributes($item)
    {
        return $this
            ->withSku($item['sku'])
            ->withUnitPrice($item['gross_price'])
            ->withQuantity($item['quantity'])
            ->withDiscount($item['gross_discount'])
        ;
    }
    
    public function withItemAssignedId($id)
    {
        return $this->andWhere(['=', 'item_assigned_id', $id]);
    }

    public function requiringSealing()
    {
        return $this
            ->joinWith('product')
            ->andWhere(['=', 'products.requires_sealing', 1])
        ;
    }
    
    public function linkedToExpo($id)
    {
        return $this->andWhere(['=', 'digital_receipts.expo_id', $id]);
    }


    /**
     * {@inheritdoc}
     * @return Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

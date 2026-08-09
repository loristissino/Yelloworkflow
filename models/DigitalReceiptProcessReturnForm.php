<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * DigitalReceiptProcessReturnForm is the model behind the login form.
 *
 */
class DigitalReceiptProcessReturnForm extends Model
{
    public $receipt;
    public $items;
    public $reason;
    /** @var array Holds [item_id => quantity_to_return] */
    public $returnQuantities = [];

    public function __construct($receipt, $items=[], $config = []) 
    {
        $this->receipt = $receipt;
        $this->items = $items;
        
        /*print_r($items);
        die();
        */
        // Initialize the array with 0s for each item ID
        foreach ($items as $item) {
            if (isset($item['id'])){
                $this->returnQuantities[$item['id']] = 0;
            }
        }
        
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['returnQuantities', 'safe'],
            ['returnQuantities', 'validateQuantities'],
            [['reason'], 'string', 'max' => 50],
            [['reason'], 'required'],
        ];
    }

    public function validateQuantities($attribute, $params)
    {
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $id = $item['item_assigned_id'];
            $inputQty = $this->returnQuantities[$id] ?? 0;
            $maxQty = $item['quantity'];

            if ($inputQty < 0) {
                $this->addError($attribute, Yii::t('app', 'Quantity cannot be negative.'));
            }
            if ($inputQty > $maxQty) {
                $this->addError($attribute, Yii::t('app', "You cannot return more than {n} for item: {desc}", [
                    'n' => $maxQty,
                    'desc' => $item['description']
                ]));
            }
            $totalQuantity += $inputQty;
        }
        if ($totalQuantity == 0) {
            $this->addError($attribute, Yii::t('app', 'No item is being returned.'));
        }
    }

    public function attributeLabels()
    {
        return [
            'digitalReceipt' => Yii::t('app', 'Digital Receipt'),
            'returnQuantities' => Yii::t('app', 'Items to Return'),
            'reason' => Yii::t('app', 'Reason'),
        ];
    }


}

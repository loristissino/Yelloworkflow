<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "digital_receipt_types".
 *
 * @property int $id
 * @property string $title
 * @property string $label
 * @property string $color
 * @property string $explanation
 * @property string $description
 * @property string $issued_text
 * @property string $voiding_text
 * @property string $return_text
 * @property string $sequential_number_code
 * @property int $status
 * @property float $amount_soft_limit
 * @property float $amount_hard_limit
 * @property int $validator
 * @property int $environment
 *
 * @property DigitalReceipt[] $digitalReceipts
 * @property Product[] $products
 */
class DigitalReceiptType extends \yii\db\ActiveRecord
{
    use  ModelTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'digital_receipt_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'label', 'explanation', 'color', 'description', 'sequential_number_code', 'validator', 'environment'], 'required'],
            [['status'], 'integer'],
            [['amount_soft_limit', 'amount_hard_limit'], 'number'],
            [['title', 'label', 'explanation', 'issued_text', 'voiding_text', 'return_text'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['sequential_number_code'], 'string', 'max' => 5],
            [['validator'], 'string', 'max' => 100],
            [['environment'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'label' => Yii::t('app', 'Label'),
            'explanation' => Yii::t('app', 'Explanation'),
            'color' => Yii::t('app', 'Color'),
            'description' => Yii::t('app', 'Description'),
            'issued_text' => Yii::t('app', 'Receipt Issued Text'),
            'voiding_text' => Yii::t('app', 'Voiding Receipt Text'),
            'return_text' => Yii::t('app', 'Receipt for Return'),
            
            'sequential_number_code' => Yii::t('app', 'Sequential Number Code'),
            'status' => Yii::t('app', 'Status'),
            'amount_soft_limit' => Yii::t('app', 'Amount Soft Limit'),
            'amount_hard_limit' => Yii::t('app', 'Amount Hard Limit'),
        ];
    }

    public function __toString() {
        return sprintf('%s -- %s (%s)', $this->label, $this->title, $this->explanation);
    }

    /**
     * Gets query for [[DigitalReceipts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceipts()
    {
        return $this->hasMany(DigitalReceipt::className(), ['digital_receipt_type_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['digital_receipt_type_id' => 'id']);
    }
    
    public static function getDigitalReceiptTypesAsArray($orderBy)
    {
        return
          self::find()
            //->active()
            ->orderBy($orderBy)
            ->select(['title'])
            ->indexBy('id')
            ->column()
            ;
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'digital_receipt_type_id',
            'prompt' => 'Choose the digital receipt type',
            'order_by' => ['id' => SORT_ASC, 'title' => SORT_ASC],
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getDigitalReceiptTypesAsArray($options['order_by']),
                ["prompt"=>$options['prompt']]
            );
    }

    /**
     * {@inheritdoc}
     * @return DigitalReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DigitalReceiptTypeQuery(get_called_class());
    }

}


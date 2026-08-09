<?php

namespace app\models;

use Yii;
use app\components\views\DataList;
use yii\helpers\Html;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $digital_receipt_type_id
 * @property int|null $organizational_unit_id
 * @property string $sku
 * @property string $shop_code
 * @property int $status
 * @property string|null $isbn
 * @property string|null $author
 * @property string $description
 * @property string $long_description
 * @property string|null $url
 * @property float $unit_price
 * @property float $max_discount
 * @property float $standard_discount
 * @property float $internal_discount
 * @property string $vat_rate_code
 * @property string|null $notes
 * @property string|null $extra_info_required
 * @property int $requires_sealing
 * @property int|null $sales_account_id
 * @property int|null $discounts_account_id
 * @property int|null $returns_account_id
 * 
 * @property DigitalReceiptLine[] $digitalReceiptLines
 * @property DigitalReceiptType $digitalReceiptType
 * @property OrganizationalUnit $organizationalUnit
 * @property Account $salesAccount
 * @property Account $discountsAccount
 * @property Account $returnsAccount
 * 
 */
class Product extends \yii\db\ActiveRecord
{
    private static $_cachedProducts = false;
    
    use ModelTrait;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['digital_receipt_type_id', 'sku', 'description', 'long_description', 'unit_price', 'vat_rate_code', 'rank'], 'required'],
            [['digital_receipt_type_id', 'organizational_unit_id', 'status', 'requires_sealing'], 'integer'],
            [['unit_price', 'max_discount', 'standard_discount', 'internal_discount', 'rank'], 'number'],
            [['notes'], 'string'],
            [['sku', 'ecommerce_code', 'author', 'extra_info_required'], 'string', 'max' => 100],
            [['isbn'], 'string', 'max' => 13],
            [['description'], 'string', 'max' => 50],
            [['long_description', 'url'], 'string', 'max' => 500],
            [['vat_rate_code'], 'string', 'max' => 5],
            [['digital_receipt_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DigitalReceiptType::className(), 'targetAttribute' => ['digital_receipt_type_id' => 'id']],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
            [['sales_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['sales_account_id' => 'id']],
            [['discounts_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['discounts_account_id' => 'id']],
            [['returns_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['returns_account_id' => 'id']],
            ['sku', 'unique', 'filter' => function($query) {
                if (!$this->isNewRecord) {
                    $query->andWhere(['!=', 'id', $this->id]);
                }
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'digital_receipt_type_id' => Yii::t('app', 'Digital Receipt Type'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'sales_account_id' => Yii::t('app', 'Sales Account'),
            'discounts_account_id' => Yii::t('app', 'Discounts Account'),
            'returns_account_id' => Yii::t('app', 'Returns Account'),
            'sku' => Yii::t('app', 'Sku'),
            'ecommerce_code' => Yii::t('app', 'E-Commerce Code'),
            'status' => Yii::t('app', 'Is Active?'),
            'rank' => Yii::t('app', 'Rank'),
            'isbn' => Yii::t('app', 'Isbn'),
            'author' => Yii::t('app', 'Author'),
            'description' => Yii::t('app', 'Description'),
            'long_description' => Yii::t('app', 'Long Description'),
            'url' => Yii::t('app', 'Url'),
            'unit_price' => Yii::t('app', 'Unit Price'),
            'max_discount' => Yii::t('app', 'Maximum Discount'),
            'standard_discount' => Yii::t('app', 'Standard Discount'),
            'internal_discount' => Yii::t('app', 'Internal Discount'),
            'vat_rate_code' => Yii::t('app', 'Vat Rate Code'),
            'notes' => Yii::t('app', 'Notes'),
            'extra_info_required' => Yii::t('app', 'Extra Info Required'),
            'requires_sealing' => Yii::t('app', 'Requires Sealing'),
        ];
    }

    /**
     * Gets query for [[DigitalReceiptLines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceiptLines()
    {
        return $this->hasMany(DigitalReceiptLine::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[DigitalReceiptType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceiptType()
    {
        return $this->hasOne(DigitalReceiptType::className(), ['id' => 'digital_receipt_type_id']);
    }

    /**
     * Gets query for [[OrganizationalUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationalUnit()
    {
        return $this->hasOne(OrganizationalUnit::className(), ['id' => 'organizational_unit_id']);
    }

    public function getSalesAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'sales_account_id']);
    }

    public function getDiscountsAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'discounts_account_id']);
    }

    public function getReturnsAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'returns_account_id']);
    }
    
    public function isSalableByOrganizationalUnit($organizationalUnit) {
        if (!$this->organizational_unit_id) {
            return true;
        }
        return $this->organizational_unit_id == $organizationalUnit->id;
    }
    
    public static function getActiveProductsAsArray($orderBy)
    {
        return
          self::find()
            ->active()
            ->orderBy($orderBy)
            ->select(['description'])
            ->indexBy('id')
            ->column()
            ;
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'product_id',
            'prompt' => 'Choose the product',
            'order_by' => ['rank' => SORT_ASC, 'description' => SORT_ASC],
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getActiveProductsAsArray($options['order_by']),
                ["prompt"=>$options['prompt']]
            );
    }
    
    public static function getDropdownForModalForm($organizationalUnit, $type='select', $options=[])
    {
        if (!self::$_cachedProducts){
            $digitalReceiptType = Yii::$app->session->get('digitalReceiptType');
            // 1. Search Logic
            $products = Product::find()
                ->active()
                ->andWhere(['digital_receipt_type_id' => $digitalReceiptType ? $digitalReceiptType->id : 0])
                ->salableByOrganizationalUnit($organizationalUnit)
                ->select(['id', 'description', 'unit_price', 'isbn', 'max_discount', 'standard_discount', 'extra_info_required'])
                ->orderBy(['rank'=> SORT_ASC, 'description' => SORT_ASC])
                ->asArray()
                ->all();
            self::$_cachedProducts = $products;
        }
        else {
            $products = self::$_cachedProducts;
        }

        $items = $products;
        
        $html = '';
       
        switch($type) {
            case 'input':
                $html.=Html::tag('input', null, $options);
                $html.="<datalist id='${options['list']}'>\n";
                break;
            case 'select':
                $html.=Html::beginTag('select', $options);
                break;
            default:
                die("not a valid type");
        }
        
        foreach($items as $item) {
            $attrs = [];
            // $description = $item['isbn'] ? Yii::t('app', 'Book «{title}»', ['title'=>$item['description']]) : $item['description'];
            $description = $item['description'];
            $attrs['data-original_price']=$item['unit_price'];
            $attrs['data-isbn']=$item['isbn'];
            $attrs['isbn']=$item['isbn'] ? ' ISBN ' . $item['isbn']: "";
            $attrs['data-max-discount']=$item['max_discount'];
            $attrs['data-standard_discount']=$item['standard_discount'];
            $attrs['data-extra_info_required']=Html::encode($item['extra_info_required']);
            switch($type) {
                case 'input':
                    $text = Yii::$app->formatter->asCurrency($attrs['data-original_price']) . ($item['isbn'] ? ' ISBN ' . $item['isbn']: "");
                    $attrs['value']=Html::encode(c);
                    $attrs['id']=$item['id'];
                    break;
                case 'select':
                    $text = Html::encode($description) . ' - ' . Yii::$app->formatter->asCurrency($attrs['data-original_price']);
                    $attrs['value']=$item['id'];
                    $attrs['id']='s_' . $item['id'];
                    $attrs['data-id']=$item['id'];
                    $attrs['data-description'] = Html::encode($description);
                    break;
            }
            $html.="<option ";
            foreach($attrs as $key=>$value) {
                $html.="$key='$value' "; 
            }
            $html.=">$text</option>\n";
        }

        switch($type) {
            case 'input':
                $html.="</datalist>";
                break;
            case 'select':
                $html.=Html::endTag('select');
                break;
        }

        return $html;
    }

    public function beforeSave($insert){
        if (!trim($this->isbn)){
            $this->isbn = null;
        }
        if (!trim($this->author)){
            $this->author = null;
        }
        if (!trim($this->ecommerce_code)){
            $this->ecommerce_code = null;
        }
        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes)
    {
        \app\components\LogHelper::log($insert ? 'created':'updated', $this, ['excluded'=>[
            'id',
        ]]);
        return parent::afterSave($insert, $changedAttributes);
    }

    public function cloneModel()
    {
        $model = new Product();
        $model->attributes = $this->attributes;
        $model->description .= ' - ' . Yii::t('app', '(Copy)');
        $model->id = null;
        $model->ecommerce_code = null;
        $model->sku .= ' - ' . Yii::t('app', '(Copy)');
        $model->save(false);
        
        return $model;
    }

    
    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
}

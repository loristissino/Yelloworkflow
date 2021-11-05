<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction_template_postings".
 *
 * @property int $id
 * @property int $transaction_template_id
 * @property int $rank
 * @property int $account_id
 * @property string $dc D=debit, C=credit
 * @property float $amount
 * 
 *
 * @property TransactionTemplate $transactionTemplate
 * @property Account $account
 */
class TransactionTemplatePosting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_template_postings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_template_id', 'rank', 'account_id', 'dc'], 'required'],
            [['transaction_template_id', 'rank', 'account_id'], 'integer'],
            [['dc'], 'string', 'max' => 1],
            [['amount'], 'number'],
            [['transaction_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionTemplate::className(), 'targetAttribute' => ['transaction_template_id' => 'id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'transaction_template_id' => Yii::t('app', 'Transaction Template'),
            'rank' => Yii::t('app', 'Rank'),
            'account_id' => Yii::t('app', 'Account ID'),
            'dc' => Yii::t('app', 'Debit/Credit'),
            'amount' => Yii::t('app', 'Amount'),
        ];
    }

    /**
     * Gets query for [[TransactionTemplate]].
     *
     * @return \yii\db\ActiveQuery|TransactionTemplateQuery
     */
    public function getTransactionTemplate()
    {
        return $this->hasOne(TransactionTemplate::className(), ['id' => 'transaction_template_id']);
    }

    /**
     * Gets query for [[Account]].
     *
     * @return \yii\db\ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }
    
    public function setTransactionTemplate(TransactionTemplate $template)
    {
        $this->transaction_template_id = $template->id;
        return $this;
    }

    public static function getDCTypes()
    {
        return [
            'D' => Yii::t('app', 'Debit'),
            'C' => Yii::t('app', 'Credit'),
            '$' => Yii::t('app', 'Predetermined Amount'),
        ];
    }
    
    public function getFormattedAmount()
    {
        return Yii::$app->formatter->asCurrency($this->amount);
    }
    
    public static function getDebitCreditDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'dc',
            'prompt' => 'Choose the type of posting',
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getDCTypes(),
                ["prompt"=>$options['prompt']]
            );
    }
    
    public function getViewDC()
    {
        return self::getDCTypes()[$this->dc];
    }
    
    public function getIconDC()
    {
        $padding = 30;
        switch($this->dc) {
            case 'D':
                $icon = 'indent-right';
                $color = '#008000';
                break;
            case 'C':
                $icon = 'indent-left';
                $color = '#800080';
                $padding = 60;
                break;
            case '$':
                $icon = 'transfer';
                $color = '#7F7F7F';
                $padding = 90;
                break;
        }

        $html = '<span style="color: '.$color.'; padding-left: ' . $padding . 'px" class="glyphicon glyphicon-'.$icon.'"></span>';
        
        if ($this->dc == '$') {
            $html .= ' ' . $this->formattedAmount;
        }
        
        return $html;
    }

    /**
     * {@inheritdoc}
     * @return TransactionTemplatePostingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionTemplatePostingQuery(get_called_class());
    }
}

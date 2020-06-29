<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "postings".
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $account_id
 * @property float $amount
 *
 * @property Transaction $transaction
 * @property Account $account
 */
class Posting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'postings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'account_id', 'amount'], 'required'],
            [['transaction_id', 'account_id'], 'integer'],
            [['amount'], 'number'],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::className(), 'targetAttribute' => ['transaction_id' => 'id']],
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
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'amount' => Yii::t('app', 'Amount'),
        ];
    }

    /**
     * Gets query for [[Transaction]].
     *
     * @return \yii\db\ActiveQuery|TransactionQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }

    public function getPeriodicalReports()
    {
        return $this->hasMany(PeriodicalReport::className(), ['id' => 'periodical_report_id'])->viaTable('transactions', ['id' => 'transaction_id']);
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
    
    public function getFormattedAmount()
    {
        return  Yii::$app->formatter->asCurrency(abs($this->amount));
    }

    public function getFormattedDebitAmount()
    {
        return $this->amount >=0 ? Yii::$app->formatter->asCurrency(abs($this->amount)): '';
    }

    public function getFormattedCreditAmount()
    {
        return $this->amount <0 ? Yii::$app->formatter->asCurrency(abs($this->amount)): '';
    }
    
    public function getDescription($account=null)
    {
        if (!$account)
            $account = $this->getAccount()->one();        
        return $account->getHeader($this->amount);
    }
    
    public function getAmountDescribed($account=null)
    {
        if (!$account)
            $account = $this->getAccount()->one();
        
        return sprintf('%s: %s', $this->getDescription($account), $this->getFormattedAmount());
    }
    
    public function __toString()
    {
        return sprintf('id: %d, account: «%s», amount: %s', $this->id, $this->getAccount()->one()->name, $this->amount);
    }

    /**
     * {@inheritdoc}
     * @return PostingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostingQuery(get_called_class());
    }
}

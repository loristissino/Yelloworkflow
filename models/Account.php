<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\data\SqlDataProvider;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property int|null $organizational_unit_id
 * @property int|null $rank
 * @property string $name
 * @property string $reversed_name
 * @property int $status
 * @property string|null $code
 * @property string $debits_header
 * @property string $credits_header
 * @property string $represents
 * @property string|null $enforced_balance
 * @property int $shown_in_ou_view
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrganizationalUnit $organizationalUnit
 * @property Posting[] $postings
 * @property TransactionTemplatePosting[] $transactionTemplatePostings
 */
class Account extends \yii\db\ActiveRecord
{
    use ModelTrait;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounts';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organizational_unit_id', 'rank', 'status', 'shown_in_ou_view'], 'integer'],
            [['name', 'debits_header', 'credits_header'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['reversed_name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 40],
            [['code', 'debits_header', 'credits_header'], 'string', 'max' => 60],
            [['represents', 'enforced_balance'], 'string', 'max' => 1],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'rank' => Yii::t('app', 'Rank'),
            'name' => Yii::t('app', 'Name'),
            'reversed_name' => Yii::t('app', 'Reversed Name'),
            'status' => Yii::t('app', 'Is Active?'),
            'code' => Yii::t('app', 'Code'),
            'debits_header' => Yii::t('app', 'Debits Header'),
            'credits_header' => Yii::t('app', 'Credits Header'),
            'represents' => Yii::t('app', 'Represents'),
            'enforced_balance' => Yii::t('app', 'Enforced Balance'),
            'shown_in_ou_view' => Yii::t('app', 'Shown in OU view'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[OrganizationalUnit]].
     *
     * @return \yii\db\ActiveQuery|OrganizationalUnitQuery
     */
    public function getOrganizationalUnit()
    {
        return $this->hasOne(OrganizationalUnit::className(), ['id' => 'organizational_unit_id']);
    }

    /**
     * Gets query for [[Postings]].
     *
     * @return \yii\db\ActiveQuery|PostingQuery
     */
    public function getPostings()
    {
        return $this->hasMany(Posting::className(), ['account_id' => 'id']);
    }

    /**
     * Gets query for [[TransactionTemplatePostings]].
     *
     * @return \yii\db\ActiveQuery|TransactionTemplatePostingQuery
     */
    public function getTransactionTemplatePostings()
    {
        return $this->hasMany(TransactionTemplatePosting::className(), ['account_id' => 'id']);
    }
    
    public function beforeSave($insert)
    {
        if (!$this->rank)
            $this->rank = 1;
        return parent::beforeSave($insert);
    }

    public function getViewLink($options=[], $year=3000)
    {
        return Yii\helpers\Html::a($this->name, ['statements/view', 'id'=>$this->id, 'year'=>$year], $options);
    }

    public function getHeader($amount)
    {
        return $amount > 0 ? $this->debits_header : $this->credits_header;
    }
    
    public function getBalanceDescription($balance)
    {
        if (!$balance) return '';
        return $balance >=0 ?
            sprintf('%s − %s', $this->debits_header, $this->credits_header)
            : 
            sprintf('%s − %s', $this->credits_header, $this->debits_header)
            ;
    }

    public static function getBalancesDataProviderForRealAccounts($organizationalUnitId, $weight = null, $before = null)
    {
        if (!$weight) {
            $weight = 2047;
        }
        if (!$before){
            $before = '2100-01-01';
        }
        
        $sql = "SELECT `accounts`.`id` as `id`, `accounts`.`name` as `name`, `accounts`.`enforced_balance` as `enforced_balance`, SUM(`amount`) as `amount_sum` FROM `postings` JOIN `accounts` ON `postings`.`account_id`=`accounts`.`id` JOIN `transactions` ON `postings`.`transaction_id`=`transactions`.`id` JOIN `periodical_reports` ON `periodical_reports`.`id` = `transactions`.`periodical_report_id` WHERE `periodical_reports`.`organizational_unit_id` = :id AND `accounts`.`represents`='R' AND `transactions`.`wf_status` IN " . Transaction::getSqlSetForStatuses($weight) . " AND `transactions`.`date` <= :before GROUP BY `accounts`.`id`, `accounts`.`name`, `accounts`.`debits_header`, `accounts`.`credits_header`";

        return new SqlDataProvider([
            'sql' => $sql,
            'params' => [':id' => $organizationalUnitId, ':before' => $before],
            'sort' => [
                'attributes' => [
                    'rank',
                    'name',
                    ],
                ],
            'pagination' => false,
            ]
        );
    }

    public static function getBalancesDataProviderForTemporaryAccounts($organizationalUnitId, $year)
    {
       $sql = "SELECT `accounts`.`id` as `id`, `accounts`.`name` as `name`, `accounts`.`debits_header` as `debits_header`, `accounts`.`credits_header` as `credits_header`, SUM(`amount`) as `amount_sum` FROM `postings` JOIN `accounts` ON `postings`.`account_id`=`accounts`.`id` JOIN `transactions` ON `postings`.`transaction_id`=`transactions`.`id` JOIN `periodical_reports` ON `periodical_reports`.`id` = `transactions`.`periodical_report_id` WHERE `periodical_reports`.`organizational_unit_id` = :id AND `accounts`.`represents` IN ('E', 'C', 'S', 'D') AND `transactions`.`wf_status` <> 'TransactionWorkflow/rejected' AND `transactions`.`date` >= MAKEDATE(:year, 1) AND `transactions`.`date` < MAKEDATE(:year +1, 1) GROUP BY `accounts`.`id`, `accounts`.`name`, `accounts`.`debits_header`, `accounts`.`credits_header`";
       
        return new SqlDataProvider([
            'sql' => $sql,
            'params' => [':id' => $organizationalUnitId, ':year' => $year],
            'sort' => [
                'attributes' => [
                    'rank',
                    'name',
                    ],
                ],
            'pagination' => false,
            ]
        );
    }

    public static function getActiveAccountsAsArray($order_by)
    {
        return
          self::find()
            ->active()
            ->orderBy($order_by)
            ->select(['name'])
            ->indexBy('id')
            ->column()
            ;
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'account_id',
            'prompt' => 'Choose the account',
            'order_by' => ['rank' => SORT_ASC, 'name' => SORT_ASC],
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getActiveAccountsAsArray($options['order_by']),
                ["prompt"=>$options['prompt']]
            );
    }
    
    public function getRepresentsView()
    {
        return Html::tag('span', '', 
            [
                'class'=>'glyphicon ' . self::getPossibleTypesMetadata()[$this->represents]['icon'], 
                'style'=>'color: ' . self::getPossibleTypesMetadata()[$this->represents]['color'],
                'title'=> sprintf('%s: %s', $this->represents, self ::getPossibleTypes()[$this->represents]),
            ]
        );
    }

    public function getStatusView()
    {
        return $this->getBooleanRepresentation($this->status);
    }
    
    public function __toString()
    {
        return $this->name;
    }

    public static function getPossibleWaysOfBeingShownInOUView()
    {
        return [
            0 => Yii::t('app', 'Not shown'),
            1 => Yii::t('app', 'Shown "as is"'),
            2 => Yii::t('app', 'Shown reversed'),
        ];
    }

    public static function getPossibleWaysOfBeingShownInOUViewDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'shown_in_ou_view',
            'prompt' => 'How is this account shown in OU view?',
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getPossibleWaysOfBeingShownInOUView(),
                ["prompt"=>$options['prompt']]
            );
    }

    public static function getPossibleTypes()
    {
        return [
            'R' => Yii::t('app', 'Real value'),
            'E' => Yii::t('app', 'Expense'),
            'D' => Yii::t('app', 'Donation'),
            'C' => Yii::t('app', 'Contribution'),
            'S' => Yii::t('app', 'Sale'),
            'O' => Yii::t('app', 'Other'),
        ];
    }
    
    public static function getPossibleTypesMetadata()
    {
        return [
            'R' => ['icon'=>'glyphicon-equalizer', 'color'=>'#FFA500'],
            'E' => ['icon'=>'glyphicon-log-out', 'color'=>'#800080'],
            'D' => ['icon'=>'glyphicon-log-in', 'color'=>'#008000'],
            'C' => ['icon'=>'glyphicon-log-in', 'color'=>'#0000FF'],
            'S' => ['icon'=>'glyphicon-tags', 'color'=>'#0000FF'],
            'O' => ['icon'=>'glyphicon-question-sign', 'color'=>'#800080'],
        ];
    }

    public static function getPossibleTypesDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'represents',
            'prompt' => 'What does this account represent?',
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getPossibleTypes(),
                ["prompt"=>$options['prompt']]
            );
    }

    public static function getPossibleEnforcedBalances()
    {
        return [
            '-' => Yii::t('app', 'No enforcement'),
            'D' => Yii::t('app', 'A Debits Balance is required'),
            'C' => Yii::t('app', 'A Credits Balance is required'),
        ];
    }

    public static function getPossibleEnforcedBalancesDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'enforced_balance',
            'prompt' => 'Choose the type of encorcement for balances',
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getPossibleEnforcedBalances(),
                ["prompt"=>$options['prompt']]
            );
    }

    public static function getBulkActionMessage($action)
    {
        $messages = [
        /*
            'increaseYear' => "Year increased for {count,plural,=0{no books} =1{one book} other{# books}}.",
            'reserve' => "Reservation made for {count,plural,=0{no books} =1{one book only} other{# books}}",
            'send' => "{count,plural,=0{no books have} =1{one book has} other{# books have}} been sent.",
            'delete' => "{count,plural,=0{no books have} =1{one book has} other{# books have}} been deleted.",
        */
        ];
        return ArrayHelper::getValue($messages, $action, '');
    }

    /**
     * {@inheritdoc}
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }
}

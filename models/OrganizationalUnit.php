<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "organizational_units".
 *
 * @property int $id
 * @property int $rank
 * @property int $status
 * @property string $name
 * @property string|null $email
 * @property string|null $url
 * @property string $last_designation
 * @property string $notes
 * @property float $ceiling_amount
 * @property int $possible_actions
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Account[] $accounts
 * @property Affiliation[] $affiliations
 * @property User[] $users
 * @property ExpenseType[] $expenseTypes
 * @property PeriodicalReport[] $periodicalReports
 * @property Project[] $projects
 * @property TransactionTemplate[] $transactionTemplates
 */
class OrganizationalUnit extends \yii\db\ActiveRecord
{
    const HAS_OWN_PROJECTS        =        1;
    const HAS_OWN_CASH            =        2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizational_units';
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
            [['rank', 'status', 'name'], 'required'],
            [['rank', 'status', 'created_at', 'updated_at'], 'integer'],
            [['last_designation_date'], 'safe'],
            [['notes'], 'string'],
            [['name', 'email'], 'string', 'max' => 100],
            [['ceiling_amount'], 'number', 'min'=>0, 'max' => 100000],
            [['possible_actions'], 'number', 'min' => 0],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rank' => Yii::t('app', 'Rank'),
            'status' => Yii::t('app', 'Is Active?'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'url' => Yii::t('app', 'Url'),
            'last_designation_date' => Yii::t('app', 'Last Designation Date'),
            'notes' => Yii::t('app', 'Notes'),
            'ceiling_amount' => Yii::t('app', 'Ceiling Amount'),
            'possible_actions' => Yii::t('app', 'Possible Actions'), // reserved for future use
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Accounts]].
     *
     * @return \yii\db\ActiveQuery|AccountQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['organizational_unit_id' => 'id']);
    }

    /**
     * Gets query for [[Affiliations]].
     *
     * @return \yii\db\ActiveQuery|AffiliationQuery
     */
    public function getAffiliations()
    {
        return $this->hasMany(Affiliation::className(), ['organizational_unit_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('affiliations', ['organizational_unit_id' => 'id']);
    }
    
    public function hasUser(User $user)
    {
        return $this->getAffiliations()->withUser($user)->one() !== null;
    }
    
    public function hasLoggedInUser()
    {
        return Yii::$app->user->isGuest? false : $this->hasUser(Yii::$app->user->identity);
    }

    /**
     * Gets query for [[ExpenseTypes]].
     *
     * @return \yii\db\ActiveQuery|ExpenseTypeQuery
     */
    public function getExpenseTypes()
    {
        return $this->hasMany(ExpenseType::className(), ['organizationa_unit_id' => 'id']);
    }

    /**
     * Gets query for [[PeriodicalReports]].
     *
     * @return \yii\db\ActiveQuery|PeriodicalReportQuery
     */
    public function getPeriodicalReports()
    {
        return $this->hasMany(PeriodicalReport::className(), ['organizational_unit_id' => 'id']);
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery|ProjectQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['organizational_unit_id' => 'id']);
    }

    /**
     * Gets query for [[TransactionTemplates]].
     *
     * @return \yii\db\ActiveQuery|TransactionTemplateQuery
     */
    public function getTransactionTemplates()
    {
        return $this->hasMany(TransactionTemplate::className(), ['organizational_unit_id' => 'id']);
    }
    
    public function getPeriodicalReportByDate($date)
    {
        return $this->getPeriodicalReports()->draft()->withWithinDate($date)->orderBy(['end_date'=>SORT_DESC])->one();
    }
    
    public function getViewLink($options=[])
    {
        return Html::a($this->name, ['organizational-units/view', 'id'=>$this->id], $options);
    }

    public static function getActiveOrganizationalUnitsAsArray($options)
    {
        return
          self::find()
            ->active()
            ->withPossibileActions($options['possible_actions'])
            ->orderBy($options['order_by'])
            ->select(['name'])
            ->indexBy('id')
            ->column()
            ;
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'organizational_unit_id',
            'prompt' => 'Choose the organizational unit',
            'order_by' => ['rank' => SORT_ASC, 'name' => SORT_ASC],
            'possible_actions' => null,
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                self::getActiveOrganizationalUnitsAsArray($options),
                ["prompt"=>$options['prompt']]
            );
    }
    
    public function getFormattedCeilingAmount()
    {
        return  Yii::$app->formatter->asCurrency($this->ceiling_amount);
    }    

    public function getSignificantLedgers()
    {
        if (! $this->hasOwnCash)
            return '';
        $significantAccounts = Account::find()->shownInOUView()->all();
        foreach ($significantAccounts as $account) {
            $links[] = sprintf('%s: %s', $account->name, $this->getLinkToLedger($account));
        }
        return join($links, '<br />');
    }
    
    public function getHasOwnCash()
    {
        return $this->possible_actions & self::HAS_OWN_CASH;
    }

    public function getLinkToLedger(Account $account)
    {
        return Html::a(Yii::$app->formatter->asCurrency($this->computeBalance($account)), ['statements/ledger', 'account'=>$account->id, 'ou'=>$this->id]);
    }
    
    public function computeBalance(Account $account)
    {
        $amount = Posting::find()->select('postings.*, transactions.*')->joinWith('account')->draft(false)->withAccountId($account->id)->joinWith('periodicalReports')->withOrganizationalUnitId($this->id)->sum('amount');
        return $account->shown_in_ou_view == 1 ? $amount : - $amount;
    }

    public function afterSave($insert, $changedAttributes)
    {
        \app\components\LogHelper::log($insert ? 'created':'updated', $this);
        return parent::afterSave($insert, $changedAttributes);
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     * @return OrganizationalUnitQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrganizationalUnitQuery(get_called_class());
    }
}

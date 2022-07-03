<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;

/**
 * This is the model class for table "periodical_reports".
 *
 * @property int $id
 * @property int $organizational_unit_id
 * @property string $name
 * @property string $begin_date
 * @property string $end_date
 * @property string $due_date
 * @property string $wf_status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrganizationalUnit $organizationalUnit
 * @property Transaction[] $transactions
 */
class PeriodicalReport extends \yii\db\ActiveRecord
{
    use WorkflowTrait;
        
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodical_reports';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'workflowBehavior' => [
                'class'                    => SimpleWorkflowBehavior::className(),
                'statusAttribute'          => 'wf_status',
            ],
			'fileBehavior' => [
				'class' => \nemmo\attachments\behaviors\FileBehavior::className()
			],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organizational_unit_id', 'name', 'begin_date', 'end_date'], 'required'],
            [['organizational_unit_id', 'created_at', 'updated_at'], 'integer'],
            [['begin_date', 'end_date', 'due_date'], 'safe'],
            [['required_attachments'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['wf_status'], 'string', 'max' => 40],
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
            'name' => Yii::t('app', 'Name'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'due_date' => Yii::t('app', 'Due Date'),
            'required_attachments' => Yii::t('app', 'Required Attachments'),
            'wf_status' => Yii::t('app', 'Workflow Status'),
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
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery|TransactionQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['periodical_report_id' => 'id']);
    }

    /**
     * Gets query for [[PeriodicalReportComments]].
     *
     * @return \yii\db\ActiveQuery|PeriodicalReportCommentQuery
     */
    public function getPeriodicalReportComments()
    {
        return $this->hasMany(PeriodicalReportComment::className(), ['periodical_report_id' => 'id']);
    }

    public function getIsDraft()
    {
        return $this->getWorkflowStatus()->getId() == 'PeriodicalReportWorkflow/draft';
    }

    public function getIsSubmitted()
    {
        return $this->getWorkflowStatus()->getId() == 'PeriodicalReportWorkflow/submitted';
    }

    public function getIsSubmittedEmpty()
    {
        return $this->getWorkflowStatus()->getId() == 'PeriodicalReportWorkflow/submitted-empty';
    }

    public function getViewLink($options=[])
    {
        return Yii\helpers\Html::a($this->__toString(), ['periodical-report-submissions/view', 'id'=>$this->id], $options);
    }

    public function __toString()
    {
        return $this->name;
    }    
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->enterWorkflow();
        }
        return parent::beforeSave($insert);
    }     

    public function getCanBeUpdated()
    {
        return $this->getWorkflowStatus()->getId() == 'PeriodicalReportWorkflow/draft';
    }

    public function getCanBeSeenInManagementView()
    {
        return sizeof($this->periodicalReportComments) > 0 || !$this->isDraft;
    }
    
    public function getIsOwnedByCurrentUser()
    {
        return $this->organizational_unit_id == Yii::$app->session->get('organizational_unit_id');
    }

    public function getIsEmpty()
    {
        return $this->getTransactions()->count()==0;
    }
    
    public function getIsSpread()
    {
        return $this->begin_date != $this->end_date;
    }
    
    public function getHasAllTransactionsReady()
    {
        return 
            $this->getTransactions()->withOneOfStatuses([
                'TransactionWorkflow/confirmed',
                'TransactionWorkflow/submitted',
                'TransactionWorkflow/notified',
                'TransactionWorkflow/recorded',
                'TransactionWorkflow/sealed',
                'TransactionWorkflow/handled', 
            ])->count()
            ==
            $this->getTransactions()->count() - $this->getTransactions()->withStatus('TransactionWorkflow/rejected')->count() 
            ;
    }

    public function getHasAllTransactionsRecorded()
    {
        return 
            $this->getTransactions()->withStatus('TransactionWorkflow/recorded')->count()
            ==
            $this->getTransactions()->count() - $this->getTransactions()->withStatus('TransactionWorkflow/rejected')->count()
            ;
    }

    public function saveAttachments()
    {
        $this->saveUploads(null);
        return true;
    }

    public function getSubmissionDateIsOK()
    {
        return date('Y-m-d') >= $this->end_date or !$this->organizationalUnit->hasOwnCash;
    }

    public function getRequiredAttachments()
    {
        if (!$this->required_attachments) {
            return [];
        }
        return array_map('trim', explode("\n", $this->required_attachments));
    }
    
    public function getDueDate()
    {
        return Yii::$app->formatter->asDate($this->due_date);
    }

    private function _hasRecentComments()
    {
        return sizeof($this->getPeriodicalReportComments()->after($this->LastLoggedActivityTime)->ofUser(Yii::$app->user->identity->id)->all()) > 0;
    }

    private function _runWorkflowChecks($event)
    {
        switch ($event->getEndStatus()->getId()) {
            case 'PeriodicalReportWorkflow/submitted-empty':
                if (!$this->isEmpty) {
                    $this->workflowError = 'An empty periodical report cannot have transactions.';
                    $event->invalidate($this->workflowError);
                }
                if (sizeof($this->files) < sizeof($this->getRequiredAttachments())) {
                    $this->workflowError = Yii::t('app', 'At least a required attachment is missing ({missing}).', ['missing'=>join(', ', $this->getRequiredAttachments())]);
                    $event->invalidate($this->workflowError);
                }
                if (!$this->submissionDateIsOK) {
                    $this->workflowError = 'A periodical report cannot be submitted before its end date.';
                    $event->invalidate($this->workflowError);
                }                
                break;
            case 'PeriodicalReportWorkflow/submitted':
                if ($this->isEmpty) {
                    $this->workflowError = 'A non-empty periodical report must have at least a transaction.';
                    $event->invalidate($this->workflowError);
                }
                elseif (!$this->hasAllTransactionsReady) {
                    $this->workflowError = 'Not all the transactions are ready for submission.';
                    $event->invalidate($this->workflowError);
                }
                if (sizeof($this->files) < sizeof($this->getRequiredAttachments())) {
                    $this->workflowError = Yii::t('app', 'At least a required attachment is missing ({missing}).', ['missing'=>join(', ', $this->getRequiredAttachments())]);
                    $event->invalidate($this->workflowError);
                }
                if (!$this->submissionDateIsOK) {
                    $this->workflowError = 'A periodical report cannot be submitted before its end date.';
                    $event->invalidate($this->workflowError);
                }                
                break;
            case 'PeriodicalReportWorkflow/questioned':
                if ( ! $this->_hasRecentComments() ) {
                    $this->workflowError = 'To question a periodical report at least one comment is needed.';
                    $event->invalidate($this->workflowError);
                }
                break;
            case 'PeriodicalReportWorkflow/closed':
                if (!$this->hasAllTransactionsRecorded) {
                    $this->workflowError = 'Not all the transactions have been recorded.';
                    $event->invalidate($this->workflowError);
                }
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";

        if (in_array($event->getEndStatus()->getId(), ['PeriodicalReportWorkflow/submitted', 'PeriodicalReportWorkflow/submitted-empty'])) {
            $this->due_date = null;
            $this->save();
        }
                
        if ($event->getEndStatus()->getId() == 'PeriodicalReportWorkflow/submitted') {
            foreach($this->getTransactions()->all() as $transaction) {
                if (!in_array($transaction->getWorkflowStatus()->getId(), [
                        'TransactionWorkflow/recorded',
                        'TransactionWorkflow/notified',
                        'TransactionWorkflow/sealed',
                        'TransactionWorkflow/handled',
                        'TransactionWorkflow/rejected',
                    ])) {
                    // a transaction could have been recorded, but the periodical report could have been questioned
                    // if a transaction has been notified from the office, its status should not be changed
                    $transaction->sendToStatus('submitted');
                    $transaction->save();
                    $log .= 'transaction ' . $transaction->id . "\n";
                }
            }
        }
        
        if ($event->getEndStatus()->getId() == 'PeriodicalReportWorkflow/questioned') {
            foreach($this->getTransactions()->all() as $transaction) {
                if ($transaction->getWorkflowStatus()->getId()=='TransactionWorkflow/submitted') {
                    $transaction->sendToStatus('questioned');
                    $log .= 'transaction ' . $transaction->getWorkflowStatus()->getId() . "\n";
                    $transaction->save(false);
                    $log .= 'transaction ' . $transaction->id . "\n";
                }
            }
            
            $this->due_date = date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")+2, date("Y")));
            $this->save();
        }

        if ($event->getEndStatus()->getId() == 'PeriodicalReportWorkflow/closed') {
            $today = date('Y-m-d');
            if ($this->end_date > $today) {
                // this happens for periodical reports generated automatically when the submitting organizational unit has no cash management
                $this->end_date = $today;
                $this->save();
            }
        }
        
        $options = [];
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);
                
    }

    public static function getSummaryData($type)
    {
        switch($type){
            case 'balances':
                $realAccounts = Account::find()->where(['=', 'represents', 'R'])->orderBy(['rank'=>'ASC'])->all();
                $accounts=[];
                $enforcedBalances = [];
                foreach($realAccounts as $account) {
                    $accounts[$account->name]=0;
                    $enforcedBalances[$account->name]=$account->enforced_balance;
                }
                
                $tuples = new SqlDataProvider([
                    'sql' => "SELECT SUM(`amount`) as `amount`, `organizational_units`.`name` as `ou`, `organizational_units`.`status` as `status`, `organizational_units`.`ceiling_amount` as `ceiling_amount`, `accounts`.`name` as `account`, `accounts`.`enforced_balance` as `encorced_balance` FROM `organizational_units` LEFT JOIN `periodical_reports` ON `periodical_reports`.`organizational_unit_id` = `organizational_units`.`id` LEFT JOIN `transactions` ON `transactions`.`periodical_report_id` = `periodical_reports`.`id` LEFT JOIN `postings` ON `postings`.`transaction_id` = `transactions`.`id` LEFT JOIN `accounts` ON `accounts`.`id` = `postings`.`account_id` WHERE (`accounts`.`represents` = 'R' OR `accounts`.`represents` IS NULL) AND (`transactions`.`wf_status` IN ('TransactionWorkflow/recorded', 'TransactionWorkflow/reimbursed') OR `transactions`.`wf_status` IS NULL) AND `organizational_units`.`possible_actions` & 2 GROUP BY `organizational_units`.`name`, `organizational_units`.`status`, `accounts`.`name` ORDER BY `organizational_units`.`name`, `accounts`.`rank`",
                    //'params' => [':status' => 1],
                    'pagination' => [
                        'pageSize' => 1000,
                    ],
                ]);
                
                $basicData = [];
                
                foreach($tuples->getModels() as $tuple) {
                    $basicData[$tuple['ou']]=$accounts;
                }

                foreach($tuples->getModels() as $tuple) {
                    $basicData[$tuple['ou']]['ceiling_amount'] = $tuple['ceiling_amount'];
                    $basicData[$tuple['ou']]['status'] = $tuple['status'];
                    $basicData[$tuple['ou']][$tuple['account']] = $tuple['amount'];
                }
                
                $data = [];
                
                foreach($basicData as $key=>$value) {
                    $line = [
                        'ou' => $key,
                        'ceiling_amount' => $value['ceiling_amount'],
                    ];
                    $data[]=array_merge($line, $value);
                }
                                                
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 9999,
                    ],                
                ]);
                
                return [
                    'fields'=>array_keys($accounts),
                    'enforcedBalances' => $enforcedBalances,
                    'provider'=>$dataProvider,
                ];
                
                break;
        }
    }

    public static function getRecapData($type, $ou)
    {
        switch($type){
            case 'general':
                
                $statuses = [
                    'draft',
                    'submitted',
                    'submitted-empty',
                    'questioned',
                    'closed',
                ];
                                /*
                 * SELECT `organizational_units`.`name` as `ou`, `periodical_reports`.`name` as `pr`, `periodical_reports`.wf_status as `report_status`, `transactions`.`wf_status`  AS `transaction_status`, COUNT(`transactions`.`id`) AS `number` FROM `periodical_reports` JOIN `organizational_units` ON `periodical_reports`.`organizational_unit_id` = `organizational_units`.`id` JOIN `transactions` ON `transactions`.`periodical_report_id` = `periodical_reports`.`id` WHERE `periodical_reports`.`end_date` < '2022-01-01' GROUP BY `organizational_units`.`name`, `periodical_reports`.`name`, `transactions`.`wf_status`, `periodical_reports`.`wf_status` */
                
                $tuples = new SqlDataProvider([
                    'sql' => "SELECT MIN(`periodical_reports`.`id`) as `id`, `periodical_reports`.`name` as `name`, REPLACE(`periodical_reports`.`wf_status`, 'PeriodicalReportWorkflow/', '') as `status`, COUNT(`periodical_reports`.`id`) AS `number` FROM `periodical_reports` JOIN `organizational_units` ON `organizational_units`.`id` = `periodical_reports`.`organizational_unit_id` WHERE (`organizational_units`.`possible_actions` & :action)>0 GROUP BY `periodical_reports`.`name`, `periodical_reports`.`wf_status` ORDER BY `periodical_reports`.`begin_date` DESC",
                    'params' => [':action' => OrganizationalUnit::HAS_OWN_CASH],
                    'pagination' => [
                        'pageSize' => 9999,
                    ],
                ]);
                
                $basicData = [];
                
                foreach($tuples->getModels() as $tuple) {
                    $basicData[$tuple['name']]=array_fill_keys($statuses, 0);
                }
                
                foreach($tuples->getModels() as $tuple) {
                    $basicData[$tuple['name']][$tuple['status']]=$tuple['number'];
                }
                
                $data = [];
                
                foreach($basicData as $key=>$value) {
                    $line = [
                        'name' => $key,
                    ];
                    $data[]=array_merge($line, $value);
                }
                                                                
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 1000,
                    ],                
                ]);
                
                return [
                    'fields'=>$statuses,
                    'provider'=>$dataProvider,
                ];
                
                break;
                
            case 'submitted':
                
                $statuses = [
                    'submitted',
                    'questioned',
                    'sealed',
                    'handled',
                    'rejected',
                    'prepared',
                    'notified',
                    'recorded',
                    'reimbursed',
                ];
                
                $tuples = new SqlDataProvider([
                    'sql' => "SELECT `periodical_reports`.`id` AS `id`, `periodical_reports`.`name` as `name`, `organizational_units`.`name` AS `ou`, REPLACE(`transactions`.`wf_status`, 'TransactionWorkflow/', '') AS `status`, COUNT(`transactions`.`id`) AS `number` FROM `periodical_reports` JOIN `organizational_units` ON `organizational_units`.`id` = `periodical_reports`.`organizational_unit_id` JOIN `transactions` ON `periodical_reports`.`id` = `transactions`.`periodical_report_id` WHERE (`organizational_units`.`possible_actions` & 3)>0 AND `periodical_reports`.`wf_status` IN ('PeriodicalReportWorkflow/submitted', 'PeriodicalReportWorkflow/submitted-empty') GROUP BY `periodical_reports`.`id`, `periodical_reports`.`name`, `periodical_reports`.`wf_status`, `periodical_reports`.`wf_status` ORDER BY `periodical_reports`.`begin_date` DESC",
                    'params' => [':action' => OrganizationalUnit::HAS_OWN_CASH],
                    'pagination' => [
                        'pageSize' => 9999,
                    ],
                ]);
                
                $basicData = [];
                
                foreach($tuples->getModels() as $tuple) {
                    $basicData[$tuple['id']]=array_fill_keys($statuses, 0);
                }
                
                foreach($tuples->getModels() as $tuple) {
                    $basicData[$tuple['id']][$tuple['status']]=$tuple['number'];
                    $basicData[$tuple['id']]['name'] = $tuple['name'];
                    $basicData[$tuple['id']]['ou'] = $tuple['ou'];
                }
                
                $data = [];
                
                foreach($basicData as $key=>$value) {
                    $line = [
                        'id' => $key,
                    ];
                    $data[]=array_merge($line, $value);
                }
                                                                
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 1000,
                    ],                
                ]);
                
                return [
                    'fields'=>$statuses,
                    'provider'=>$dataProvider,
                ];
                
                break;

        }
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
     * @return PeriodicalReportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PeriodicalReportQuery(get_called_class());
    }
}

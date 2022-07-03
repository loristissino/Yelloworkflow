<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\data\SqlDataProvider;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $periodical_report_id
 * @property int $transaction_template_id
 * @property string $date
 * @property string $description
 * @property int|null $project_id
 * @property int|null $event_id
 * @property string|null $notes
 * @property string|null $handling
 * @property string|null $vat_number
 * @property string|null $vendor
 * @property string $wf_status
 * @property int $user_id last updater
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Posting[] $postings
 * @property PeriodicalReport $periodicalReport
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    
    use WorkflowTrait;
    use ModelTrait;
    
    const SCENARIO_HANDLING = 'handling';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
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
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_HANDLING] = ['handling'];
        $scenarios['default'] = array_diff($scenarios['default'], $scenarios[self::SCENARIO_HANDLING]);
        return $scenarios;        
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periodical_report_id', 'date', 'description', 'wf_status', 'user_id'], 'required'],
            [['periodical_report_id', 'project_id', 'event_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'safe'],
            [['description', 'notes'], 'string', 'max' => 255],
            [['notes', 'handling'], 'string', 'max' => 255],
            [['vendor'], 'string', 'max' => 100],
            [['invoice'], 'string', 'max' => 60],
            [['wf_status'], 'string', 'max' => 40],
            [['description', 'notes', 'vendor', 'invoice'], 'filter', 'filter'=>function($value) {return trim(strip_tags($value));}],
            [['periodical_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodicalReport::className(), 'targetAttribute' => ['periodical_report_id' => 'id']],
            [['transaction_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionTemplate::className(), 'targetAttribute' => ['transaction_template_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'periodical_report_id' => Yii::t('app', 'Periodical Report'),
            'transaction_template_id' => Yii::t('app', 'Transaction Template'),
            'date' => Yii::t('app', 'Date'),
            'description' => Yii::t('app', 'Description'),
            'project_id' => Yii::t('app', 'Project'),
            'event_id' => Yii::t('app', 'Event'),
            'notes' => Yii::t('app', 'Notes'),
            'handling' => Yii::t('app', 'Handling'),
            'vat_number' => Yii::t('app', 'VAT Number'),
            'vendor' => Yii::t('app', 'Vendor'),
            'invoice' => Yii::t('app', 'Invoice or Receipt'),
            'wf_status' => Yii::t('app', 'Workflow Status'),
            'user_id' => Yii::t('app', 'User'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Postings]].
     *
     * @return \yii\db\ActiveQuery|PostingQuery
     */
    public function getPostings()
    {
        return $this->hasMany(Posting::className(), ['transaction_id' => 'id']);
    }

    /**
     * Gets query for [[PeriodicalReport]].
     *
     * @return \yii\db\ActiveQuery|PeriodicalReportQuery
     */
    public function getPeriodicalReport()
    {
        return $this->hasOne(PeriodicalReport::className(), ['id' => 'periodical_report_id']);
    }

    public function getTransactionTemplate()
    {
        return $this->hasOne(TransactionTemplate::className(), ['id' => 'transaction_template_id']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['id' => 'account_id'])->viaTable('postings', ['account_id' => 'id']);
    }

    public function getViewLink($options=[])
    {
        return Yii\helpers\Html::a($this->description, ['transaction-submissions/view', 'id'=>$this->id], $options);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->enterWorkflow();
        }
        return parent::beforeSave($insert);
    }  

    public function getPostingsView()
    {
        return $this->_getPostingsView();
    }
    
    public function getPostingsPrintingView()
    {
        $items = [];
        foreach ($this->getPostings()->all() as $posting) {
            $items[] = sprintf('<li>%s<br />&nbsp;&nbsp;(%s)</li>', $posting->account->name, $posting->getAmountDescribed($posting->account));
        }
        return '<ul>' . join ('', $items) . '</ul>';
    }
    
    public function getPostingsViewWithoutLink()
    {
        return $this->_getPostingsView(['class'=>'disabled_link']);
    }
    
    private function _getPostingsView($options=[])
    {
        $html = '';
        foreach ($this->getPostings()->all() as $posting) {
            $html .= sprintf('%s (%s)<br />', $posting->account->getViewLink($options), $posting->getAmountDescribed($posting->account));
        }
        return $html;
    }
    
    
    public function getOrganizationalUnit()
    {
        return $this->periodicalReport->organizationalUnit;
    }
    
    public function getTemplateTitle()
    {
        return $this->transactionTemplate->title;
    }
    
    public function getCanBeUpdated($status='draft')
    {
        return $this->getWorkflowStatus()->getId() == 'TransactionWorkflow/' . $status;
    }
    
    public function getCanBeConfirmed()
    {
        return ! $this->transactionTemplate->mustBeSealed;
    }

    public function getCanBeSealed()
    {
        return $this->transactionTemplate->canBeSealed and in_array($this->getWorkflowStatus()->getId(), ['TransactionWorkflow/draft', 'TransactionWorkflow/confirmed']);
    }
    
    public function confirm()
    {
        $status = 'TransactionWorkflow/confirmed';
        if ($this->canBeSentToStatus($status)) {
            return $this->sendToStatus($status);
        }
        else {
            return false;
        }
    }
    
    public function setRecorded()
    {
        $status = 'TransactionWorkflow/recorded';
        if ($this->canBeSentToStatus($status)) {
            return $this->sendToStatus($status);
        }
        else {
            return false;
        }
    }
    
    public function getMissingProject() {
        return $this->transactionTemplate->needs_project==2 and ! $this->project_id;
    }
    
    public function getIsDirectlySubmittable()
    {
        return false;
    }
    
    public function getIsDirectlyQuestionable()
    {
        return false; // this is used by the interface, in the section where we must choose to show the button or not
    }
    
    public function getHasDateInValidRange()
    {
        return $this->date >= $this->periodicalReport->begin_date and $this->date <= $this->periodicalReport->end_date;
    }
    
    public function unsetUneditableFields()
    {
        unset($this->description);
        return true;
    }

    private function _runWorkflowChecks($event)
    {
        $template = $this->transactionTemplate;
        
        switch ($event->getEndStatus()->getId()) {
            case 'TransactionWorkflow/draft':
                if (!in_array($this->periodicalReport->getWorkflowStatus()->getId(), ['PeriodicalReportWorkflow/draft', 'PeriodicalReportWorkflow/questioned'])) {
                    $this->workflowError = 'The transaction cannot be reset to draft when the periodical report has the current status.';
                    $event->invalidate($this->workflowError);
                }
                break;
            case 'TransactionWorkflow/questioned':
                if (Yii::$app->controller->id == 'transactions-management') {
                    $this->workflowError = 'The transaction can be questioned only indirectly, through the periodical report.';
                    $event->invalidate($this->workflowError);
                }    
                break;
            case 'TransactionWorkflow/confirmed':
            case 'TransactionWorkflow/sealed':
                if ($template->needs_attachment && sizeof($this->files)==0) {
                    $this->workflowError = 'This transaction must be documented with an attachment.';
                    $event->invalidate($this->workflowError);
                }
                if ($template->needs_vendor && !( $this->vendor && $this->invoice ) ) {
                    $this->workflowError = 'Vendor and supply details must be specified.';
                    $event->invalidate($this->workflowError);
                }
                if ($template->needs_project==1 && !( $this->project_id) ) {
                    $this->workflowError = 'A project must be specified.';
                    $event->invalidate($this->workflowError);
                }
                if ( $this->project_id  && $template->needs_project==0 ) {
                    $this->workflowError = 'This transaction cannot be linked to a project.';
                    $event->invalidate($this->workflowError);
                }
                if ( ( $this->vat_number || $this->vendor || $this->invoice)  && ! $template->needs_vendor ) {
                    $this->workflowError = 'This transaction cannot be linked to a vendor.';
                    $event->invalidate($this->workflowError);
                }
                if ( ! $this->hasDateInValidRange ) {
                    $this->workflowError = 'Date outside periodica report\'s range.';
                    $event->invalidate($this->workflowError);
                }
                if ( $template->request and !$this->notes ) {
                    $this->workflowError = $template->request;
                    $event->invalidate($this->workflowError);
                }
                break;
            case 'TransactionWorkflow/notified':
                if ($template->needs_attachment && sizeof($this->files)==0) {
                    $this->workflowError = 'This transaction must be documented with an attachment.';
                    $event->invalidate($this->workflowError);
                }
                if ( ! $this->hasDateInValidRange ) {
                    $this->workflowError = 'Date outside periodical report\'s range.';
                    $event->invalidate($this->workflowError);
                }
                break;
            case 'TransactionWorkflow/rejected':
                if (!$this->handling) {
                    $this->workflowError = 'The transaction cannot be rejected without a handling text.';
                    $event->invalidate($this->workflowError);
                }
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
        
        $options = [];
                
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);
        
    }

    public function addPostings(TransactionTemplate $template, $amount)
    {
        Posting::deleteAll(['=', 'transaction_id', $this->id]);
        
        foreach ($template->getTransactionTemplatePostings()->orderBy(['rank'=>SORT_ASC])->all() as $postingTemplate) {
            if ($postingTemplate->dc=='$') {
                $amountToRecord = $postingTemplate->amount;
            }
            else {
                $amountToRecord = $postingTemplate->dc == 'D' ? $amount : -$amount;
            }
            $posting = new Posting();
            $posting->transaction_id = $this->id;
            $posting->account_id = $postingTemplate->account_id;
            $posting->amount = $amountToRecord;
            $posting->save(false);
        }
    }
    
    public function invertPostings()
    {
        $connection = \Yii::$app->db;
        
        $dbTransaction = $connection->beginTransaction();
        
        try {
            foreach($this->getPostings()->all() as $posting) {
                $posting->amount = - $posting->amount;
                $posting->save();
            }
            $dbTransaction->commit();
            return true;
        }
        catch (Exception $e)
        {
            $dbTransaction->rollBack();
            return false;
        }
    }
    
    public function notify()
    {
        if ($this->getWorkflowStatus()->getId() == 'TransactionWorkflow/prepared') {
            return $this->sendToStatus('notified');
        }
        return false;
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->description, Yii::$app->formatter->asDate($this->date));
    }

    public static function getBulkActionMessage($action)
    {
        $messages = [
            'confirm' => "Status set to «confirmed» for {count,plural,=0{no transaction} =1{one transaction} other{# transactions}}.",
            'notify' => "{count,plural,=0{No transaction has been} =1{One transaction has been} other{# transactions have been}} notified.",
            'setRecorded' => "Status set to «recorded» for {count,plural,=0{no transaction} =1{one transaction} other{# transactions}}."
        ];
        return ArrayHelper::getValue($messages, $action, '');
    }

    public static function getBalance(PeriodicalReport $periodicalReport, $end=true, $statuses=[])
    {
        if (sizeof($statuses)==0) {
            $statuses = [
                'TransactionWorkflow/recorded',
                'TransactionWorkflow/reimbursed',
            ];
        }
        
        $set = "'" . join("', '", $statuses) . "'";
        
        $provider = new SqlDataProvider([
            'sql' => "SELECT `accounts`.`id`, SUM(`amount`) AS `amount_sum`, `accounts`.`name` AS `account_name` FROM `transactions` JOIN `periodical_reports` ON `periodical_report_id` = `periodical_reports`.id JOIN `postings` ON `transactions`.`id` = `postings`.`transaction_id` JOIN `accounts` ON `postings`.`account_id` = `accounts`.`id` WHERE `periodical_reports`.`organizational_unit_id`= :organizational_unit_id AND `transactions`.`wf_status` IN ($set) AND `transactions`.`date` " . ($end ? '<=' : '<') . " :date AND `accounts`.`represents` = 'R' GROUP BY `accounts`.`id`, `accounts`.`name` HAVING `amount_sum` <> 0 ORDER BY `accounts`.`rank`",
            'params' => [
                ':date'=> $end ? $periodicalReport->end_date : $periodicalReport->begin_date,
                ':organizational_unit_id' => $periodicalReport->organizational_unit_id,
            ],
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);
        
        return $provider;
    }

/*

*/
    public static function getKnownVendors()
    {
        $provider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT `vat_number`, `vendor` from `transactions` where `vat_number` is not null and not `vat_number` = '' order by `vendor`",
            'pagination' => [
                'pageSize' => 99999,
            ],
        ]);
        return $provider;
    }
    
    public static function getKnownVATNumbers()
    {
        $provider = self::getKnownVendors();
        $items = [];
        foreach($provider->models as $model) {
            $items[] = sprintf('%s - %s', $model['vat_number'], str_replace(['"', "'"], '', $model['vendor'])); 
        }
        return $items;
    }
    
    /**
     * {@inheritdoc}
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }
}

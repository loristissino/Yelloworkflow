<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
            [
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
            [['periodical_report_id', 'date', 'description', 'wf_status', 'user_id'], 'required'],
            [['periodical_report_id', 'project_id', 'event_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'safe'],
            [['description', 'notes'], 'string', 'max' => 255],
            [['vat_number'], 'string', 'max' => 20],
            [['vendor'], 'string', 'max' => 100],
            [['invoice'], 'string', 'max' => 60],
            [['wf_status'], 'string', 'max' => 40],
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
            'vat_number' => Yii::t('app', 'VAT Number'),
            'vendor' => Yii::t('app', 'Vendor'),
            'invoice' => Yii::t('app', 'Invoice'),
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
        $html = '';
        foreach ($this->getPostings()->all() as $posting) {
            $html .= sprintf('%s (%s)<br />', $posting->account->getViewLink(), $posting->getAmountDescribed($posting->account));
        }
        return $html;
    }
    
    public function getOrganizationalUnit()
    {
        return $this->periodicalReport->organizationalUnit;
    }
    
    public function getCanBeUpdated()
    {
        return $this->getWorkflowStatus()->getId() == 'TransactionWorkflow/draft';
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
    
    public function getMissingProject() {
        return $this->transactionTemplate->needs_project==2 and ! $this->project_id;
    }
    
    public function getIsDirectlySubmittable()
    {
        return false;
    }

    private function _runWorkflowChecks($event)
    {
        $template = $this->transactionTemplate;
        
        switch ($event->getEndStatus()->getId()) {
            case 'TransactionWorkflow/confirmed':
                if ($template->needs_attachment && sizeof($this->files)==0) {
                    $this->workflowError = 'This transaction must be documented with an attachment.';
                    $event->invalidate($this->workflowError);
                }
                if ($template->needs_vendor && !( $this->vat_number && $this->vendor && $this->invoice) ) {
                    $this->workflowError = 'Vendor details must be specified.';
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
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        /*
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
        
        $options = [];
        
        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/submitted') {
            $options['related'] = ['plannedExpenses'];
        }

        if ($event->getEndStatus()->getId() == 'ProjectWorkflow/rejected') {
            $options['related'] = ['projectComments'];
        }
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);
        
        */
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
            $this->sendToStatus('notified');
            $this->save();
            return true;
        }
        return false;
    }

    public static function getBulkActionMessage($action)
    {
        $messages = [
            'confirm' => "Status set to «confirmed» for {count,plural,=0{no transaction} =1{one transaction} other{# transactions}}.",
            'notify' => "{count,plural,=0{No transaction has been} =1{One transaction has been} other{# transactions have been}} notified.",
        ];
        return ArrayHelper::getValue($messages, $action, '');
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

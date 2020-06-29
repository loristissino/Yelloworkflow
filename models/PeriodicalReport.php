<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;

/**
 * This is the model class for table "periodical_reports".
 *
 * @property int $id
 * @property int $organizational_unit_id
 * @property string $name
 * @property string $begin_date
 * @property string $end_date
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
            [
                'class'                    => SimpleWorkflowBehavior::className(),
                'statusAttribute'          => 'wf_status',
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
            [['begin_date', 'end_date'], 'safe'],
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

    public function getIsDraft()
    {
        return $this->getWorkflowStatus()->getId() == 'PeriodicalReportWorkflow/draft';
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
    
    public function getIsOwnedByCurrentUser()
    {
        return $this->organizational_unit_id == Yii::$app->session->get('organizational_unit_id');
    }

    public function getIsEmpty()
    {
        return $this->getTransactions()->count()==0;
    }
    
    public function getHasAllTransactionsReady()
    {
        return 
            $this->getTransactions()->withStatus('TransactionWorkflow/confirmed')->count() +
            $this->getTransactions()->withStatus('TransactionWorkflow/notified')->count() +
            $this->getTransactions()->withStatus('TransactionWorkflow/recorded')->count()
            ==
            $this->getTransactions()->count()
            ;
    }

    public function getHasAllTransactionsRecorded()
    {
        return 
            $this->getTransactions()->withStatus('TransactionWorkflow/recorded')->count()
            ==
            $this->getTransactions()->count()
            ;
    }

    private function _runWorkflowChecks($event)
    {
        switch ($event->getEndStatus()->getId()) {
            case 'PeriodicalReportWorkflow/submitted-empty':
                if (!$this->isEmpty) {
                    $this->workflowError = 'An empty periodical report cannot have transactions.';
                    $event->invalidate($this->workflowError);
                }
                break;
            case 'PeriodicalReportWorkflow/submitted':
                if ($this->isEmpty) {
                    $this->workflowError = 'A non-empty periodical report must have at least a transaction.';
                    $event->invalidate($this->workflowError);
                }
                elseif (!$this->hasAllTransactionsReady) {
                    $this->workflowError = 'Not all the transactions are confirmed.';
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
                
        if ($event->getEndStatus()->getId() == 'PeriodicalReportWorkflow/submitted') {
            foreach($this->getTransactions()->all() as $transaction) {
                if ($transaction->getWorkflowStatus()->getId()!='TransactionWorkflow/recorded') {
                    // a transaction could have been recorded, but the periodical report could have been questioned
                    $transaction->sendToStatus('submitted');
                    $transaction->save();
                    $log .= 'transaction ' . $transaction->id . "\n";
                }
            }
        }
        
        file_put_contents('workflow2.txt', $log);
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

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class TransactionForm extends Model
{

    use ModelTrait;
        
    public $id = null;
    public $transaction_template_id;
    public $date;
    public $description;
    public $project_id;
    public $event_id;
    public $notes;
    public $vat_number;
    public $vendor;
    public $invoice;
    public $amount;

    public $templates = [];
    public $periodicalReport;

    public $originalTransaction = null;
    
    public $transaction =-1;
    
    public $begin_date;
    public $end_date;
    public $organizational_unit_id;
    
    public $immediateNotification;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['transaction_template_id', 'date', 'description', 'amount'], 'required'],
            [['project_id', 'notes', 'event_id', 'vat_number', 'vendor', 'invoice', 'organizational_unit_id', 'immediateNotification'], 'safe'],
            [['date'], 'date', 'format' => 'yyyy-mm-dd'],
            [['amount'], 'number', 'min' => 0, 'max'=>1000000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'transaction_template_id' => Yii::t('app', 'Template'),
            'date' => Yii::t('app', 'Date'),
            'description' => Yii::t('app', 'Description'),
            'notes' => Yii::t('app', 'Notes'),
            'project_id' => Yii::t('app', 'Project'),
            'event_id' => Yii::t('app', 'Event'),
            'vat_number' => Yii::t('app', 'VAT Number'),
            'vendor' => Yii::t('app', 'Vendor'),
            'amount' => Yii::t('app', 'Amount'),
            'invoice' => Yii::t('app', 'Invoice or Receipt'),
        ];
    }
    
    public function importDataFromTransaction(Transaction $transaction)
    {
        foreach([
            'id',
            'transaction_template_id',
            'periodicalReport',
            'date',
            'description',
            'notes',
            'project_id',
            'event_id',
            'vat_number',
            'vendor',
            'invoice',
            ] as $property) {
            $this->$property = $transaction->$property;
        }
        $this->amount = abs($transaction->postings[0]->amount);
        $this->originalTransaction = $transaction;
        $this->organizational_unit_id = $transaction->periodicalReport->organizational_unit_id;
        
        return $this;
    }
    
    public function setPeriodicalReport($date=null, $organizational_unit_id=null)
    {
        if ($organizational_unit_id === null) {
            $organizational_unit_id = $this->organizational_unit_id;
        }

        if ($date === null) {
            $date = $this->date;
        }
   
        $ou = \app\models\OrganizationalUnit::find()->active()->withId($organizational_unit_id)->one();
        if (!$ou) {
            $this->addError('request', Yii::t('app', 'Invalid Organizational Unit.'));
        }
        else {
            $periodicalReport = $ou->getPeriodicalReportByDate($date);
            if (!$periodicalReport) {
                $this->addError('request', Yii::t('app', 'Couldn\'t find an active periodical report.'));
            }
            else {
                $this->periodicalReport = $periodicalReport;
                $this->begin_date = $this->periodicalReport->begin_date;
                $this->end_date = $this->periodicalReport->end_date;
            }
        }
        return ! $this->hasErrors();
    }
        
    public function save()
    {
        $template = TransactionTemplate::find()->active()->withId($this->transaction_template_id)->one();
        if (!$template) {
            $this->addError('template_id', Yii::t('app', 'Invalid template ({id})', ['id' => $this->transaction_template_id]));
            return false;
        }
        
        if ($this->date > $this->end_date) {
            $this->addError('date', Yii::t('app', 'Maximun date for this periodical report is {date}.', ['date'=>$this->end_date]));
            return false;
        }

        if ($this->date < $this->begin_date) {
            $this->addError('date', Yii::t('app', 'Minimum date for this periodical report is {date}.', ['date'=>$this->begin_date]));
            return false;
        }
        
        $connection = \Yii::$app->db;
        
        $dbTransaction = $connection->beginTransaction();
        
        try {
            $transaction = $this->id ? $this->originalTransaction : new Transaction();
            $transaction->importSettings([
                'periodical_report_id' => $this->periodicalReport->id,
                'transaction_template_id' => $template->id,
                'user_id' => Yii::$app->user->identity->id,
                'date' => $this->date,
                'description' => $this->description,
                'notes' => $this->notes,
                'project_id' => $this->project_id,
                'event_id' => $this->project_id,
                'vat_number' => $this->vat_number,
                'vendor' => $this->vendor,
                'invoice' => $this->invoice,
            ]);

            $transaction->save(false); // FIXME it fails if validation is applied -- to investigate
            
            $transaction->addPostings($template, $this->amount, false);

            $transaction->save(true); // FIXME this is a workaround -- filters are applied now
            
            $transaction->saveUploads(null);
            
            
            $dbTransaction->commit();
            $this->id = $transaction->id;
            $this->transaction = $transaction;
            return true;
        }
        catch (Exception $e) {
            $dbTransaction->rollBack();
            return false;
        }
    }
    
    public function getIsNewRecord()
    {
        return $this->id===null;
    }
    
    public function getInitialPreview()
    {
        return $this->originalTransaction->getInitialPreview();
    }

    public function getInitialPreviewConfig()
    {
        return $this->originalTransaction->getInitialPreviewConfig();
    }
    
    public function getFiles()
    {
        return $this->originalTransaction->getFiles();
    }
    
}

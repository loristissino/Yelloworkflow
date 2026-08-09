<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use \raoul2000\workflow\base\SimpleWorkflowBehavior;
use app\models\PeriodicalReport;
use app\models\OrganizationalUnit;
use app\models\Account;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "expos".
 *
 * @property int $id
 * @property string $begin_date
 * @property string $end_date
 * @property string $name
 * @property string $city
 * @property int $organizational_unit_id
 * @property int|null $periodical_report_id
 * @property string $wf_status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrganizationalUnit $organizationalUnit
 * @property PeriodicalReport $periodicalReport
 */
class Expo extends \yii\db\ActiveRecord
{
    
    use WorkflowTrait;
    use ModelTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expos';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'workflowBehavior' => [
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
            [['begin_date', 'end_date', 'name', 'city', 'organizational_unit_id'], 'required'],
            [['id', 'organizational_unit_id', 'created_at', 'updated_at'], 'integer'],
            [['begin_date', 'end_date'], 'safe'],
            [['name', 'city', 'wf_status'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
            [['periodical_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodicalReport::className(), 'targetAttribute' => ['periodical_report_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'name' => Yii::t('app', 'Name'),
            'city' => Yii::t('app', 'City'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'periodical_report_id' => Yii::t('app', 'Periodical Report'),
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

    public function getPeriodicalReport()
    {
        return $this->hasOne(PeriodicalReport::className(), ['id' => 'periodical_report_id']);
    }

    /**
     * Gets query for [[DigitalReceipt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDigitalReceipts()
    {
        return $this->hasMany(DigitalReceipt::className(), ['expo_id' => 'id']);
    }


    public function getSales()
    {
        $sql = "

            SELECT `transactions`.`date` as `date`, `transactions`.`id` as `transaction_id`, `transactions`.`wf_status`, - `amount` as `amount`, 
            
            CASE
                WHEN `payment_method` = 'cash' THEN 'Cash'
                WHEN `payment_method` = 'electronic' THEN 'Card'
                ELSE 'Unknown'
            END as `payment_method`,
            
             `accounts`.`name` as `account_name`, `accounts`.`id` as `account_id`, `accounts`.`parent_id` as `parent_account_id` 
            FROM `postings` 
            JOIN `transactions` ON `transactions`.`id` = `postings`.`transaction_id`
            JOIN `accounts` ON `postings`.`account_id` = `accounts`.`id`
            LEFT JOIN `digital_receipts` ON `digital_receipts`.`transaction_id` = `transactions`.`id`

            WHERE `transactions`.`expo_id` = :expo_id
            AND `accounts`.`represents` = 'S'

            GROUP BY `transactions`.`date`, `transactions`.`id`, `payment_method`, `amount`, `accounts`.`name`, `accounts`.`id`, `accounts`.`parent_id`;

        ";
        
        $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'params' => ['expo_id' => $this->id],
                'totalCount' => false,
                'pagination' => false,
            ]);

            return $dataProvider;
    }

    public function getRecappedTransactions()
    {
        $sql = "

            SELECT - SUM(`amount`) as `total_amount`, 
            
            CASE
                WHEN `payment_method` = 'cash' THEN 'Cash'
                WHEN `payment_method` = 'electronic' THEN 'Card'
                ELSE 'Unknown'
            END as `payment_method`,
            
             `accounts`.`name` as `account_name`, `accounts`.`id` as `account_id`, `accounts`.`parent_id` as `parent_account_id` 
            FROM `postings` 
            JOIN `transactions` ON `transactions`.`id` = `postings`.`transaction_id`
            JOIN `accounts` ON `postings`.`account_id` = `accounts`.`id`
            LEFT JOIN (
                SELECT DISTINCT `transaction_id`, `payment_method`
                FROM `digital_receipts`
            ) dr ON dr.`transaction_id` = `transactions`.`id`

            WHERE `transactions`.`expo_id` = :expo_id
            AND `accounts`.`represents` = 'S'

            GROUP BY `payment_method`, `accounts`.`name`, `accounts`.`id`, `accounts`.`parent_id`;

        ";
        
        $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'params' => ['expo_id' => $this->id],
                'totalCount' => false,
                'pagination' => false,
            ]);

            return $dataProvider;
    }
    
    public function getAdjustments()
    {
        // Get the original data provider
        $originalDataProvider = $this->getRecappedTransactions();
        
        // Fetch all models from the data provider
        $models = $originalDataProvider->getModels();
        
        // Load your configuration
        $config = Yii::$app->params['expo_settings'];//require(Yii::getAlias('@app/config/expo-settings.php'));

        // Transform and filter the models
        $filteredModels = [];
        foreach ($models as $model) {
            $accountId = $config['account']($model);
            if ($accountId) {
                $model['new_account_id']=$accountId;
                $model['new_account_name'] = Account::findOne($accountId)->name ?? 'WRONG CONFIGURATION';
                $filteredModels[] = $model;
            }
        }
        
        // Create a new ArrayDataProvider with the modified data
        $newDataProvider = new ArrayDataProvider([
            'allModels' => $filteredModels,
            'pagination' => false,
        ]);
                
        return $newDataProvider;
    }
    
    public function createAdjustmentsTransaction(){
        $adjustments = $this->getAdjustments()->getModels();
        if (sizeof($adjustments)==0){
            return false;
        }

        if ($this->periodical_report_id) {
            $pr = $this->periodicalReport;
        }
        else {
            $pr = $this->organizationalUnit->getPeriodicalReportByDate(date('Y-m-d'));
        }
        if (!$pr) {
            return false;
        }


        $grouped = [];

        foreach ($adjustments as $item) {
            // Create a composite key from account-related fields (excluding payment_method and total_amount)
            $key = $item['account_id'] . '|' . $item['new_account_id'];
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = $item;
                unset($grouped[$key]['payment_method']); // Remove payment_method
            } else {
                $grouped[$key]['total_amount'] += $item['total_amount'];
            }
        }

        $reducedAdjustments = array_values($grouped);

        $tt = TransactionTemplate::find()->withRank(-1)->one();
        if (!$tt) {
            return false;
        }
        
        $transaction = new Transaction();
        $transaction->date = date('Y-m-d');
        $transaction->periodical_report_id = $pr->id;
        $transaction->transaction_template_id = $tt->id;
        $transaction->expo_id = $this->id;
        $transaction->user_id = Yii::$app->user->identity->id;
        $transaction->description = Yii::t('app', 'Final adjustments for Expo «{name}»', ['name'=>$this->name]);
        $transaction->save(false);
        
        if (!$transaction) {
            die("no transaction saved");
            return false;
        } 

        $totalAmount = 0;
        $counterAccount = -1;

        foreach ($reducedAdjustments as $adj) {
            $posting = new Posting();
            $posting->transaction_id = $transaction->id;
            $posting->account_id = $adj['account_id'];
            $posting->amount = $adj['total_amount'];
            $totalAmount += $adj['total_amount'];
            $counterAccount = $adj['new_account_id'];
            $posting->save();
        }

        $posting = new Posting();
        $posting->transaction_id = $transaction->id;
        $posting->account_id = $counterAccount;
        $posting->amount = - $totalAmount;
        $posting->save();
        $transaction->sendToStatus('prepared');
        $transaction->sendToStatus('notified');
        return $transaction->save(false);
    
    }
    
    public function __toString()
    {
        return $this->name;
    }

    private function _runWorkflowChecks($event)
    {
        switch ($event->getEndStatus()->getId()) {
            case 'ExpoWorkflow/activate':
                break;
            case 'ExpoWorkflow/closed':
                if ($this->end_date >= date('Y-m-d')) {
                    $this->workflowError = 'The expo cannot be marked as closed before its end date.';
                    $event->invalidate($this->workflowError);
                }
                break;
        }
    }

    private function _runWorkflowRoutines($event)
    {
        $log = "Running workflow routines...\n";
        
        $log .= $event->getTransition()->getId() . "\n";
        
        $log .= "Am I a voiding receipt? " . $this->id . "...";
    
        $log .= "My end status is " . $event->getEndStatus()->getId() . "\n";

        if ($event->getEndStatus()->getId() == 'ExpoWorkflow/active') {
            if(!$this->organizationalUnit->hasOwnCash && !$this->periodical_report_id) {
                $pr = new PeriodicalReport();
                $pr->name = Yii::t('app', 'Periodical Report for the Expo «{name}»', ['name'=>$this->name]);
                $pr->begin_date = $this->begin_date;
                $pr->end_date = $this->end_date;
                $pr->due_date = (new \DateTime($this->end_date))->modify('+ 2 weeks')->format('Y-m-d');
                $pr->organizational_unit_id = $this->organizational_unit_id;
                
                $dbTransaction = \Yii::$app->db->beginTransaction();
                try {
                    $pr->save(false);
                    $this->periodical_report_id = $pr->id;
                    $this->save();
                    $dbTransaction->commit();
                    Yii::$app->session->setFlash('info', Yii::t('app', 'A Periodical Report has been created.'));
                }
                catch (Exception $e)
                {
                    $dbTransaction->rollBack();
                    Yii::$app->session->setFlash('error', Yii::t('app', 'A Periodical Report could not be created.'));
                }
            }
        }

        if ($event->getEndStatus()->getId() == 'ExpoWorkflow/closed') {
            $this->createAdjustmentsTransaction();
        }
        
        //file_put_contents("log_" . time() . ".txt", $log);

        $options = [];
        
        \app\components\LogHelper::log($event->getEndStatus()->getId(), $this, $options);

    }
    
    public function getCanBeDeleted()
    {
        return
            $this->getWorkflowStatus()->getId() != 'ExpoWorkflow/draft'
            &&
            $this->getDigitalReceipts()->count() == 0;
        ;
    }

    public function getViewLink($options=[])
    {
        return Yii\helpers\Html::a($this->__toString(), ['expos/view', 'id'=>$this->id], $options);
    }

    public function cloneModel()
    {
        $model = new Expo();
        $model->attributes = $this->attributes;
        $model->name .= ' - ' . Yii::t('app', '(Copy)');
        $model->wf_status = null;
        $model->id = null;
        $model->periodical_report_id = null;
        $model->save(false);
        
        return $model;
    }

    /**
     * {@inheritdoc}
     * @return ExpoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExpoQuery(get_called_class());
    }
}

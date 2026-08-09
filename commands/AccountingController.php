<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\PeriodicalReport;
use app\models\OrganizationalUnit;
use app\models\DigitalReceipt;
use app\models\TransactionTemplate;
use app\models\Transaction;
use app\models\Posting;

class AccountingController extends Controller
{
    public function actionIndex()
    {
        return ExitCode::OK;
    }
    
    public function actionCreatePeriodicalReports()
    {
        // This will be called daily, we have to check whether it is the last day of the month
        if (date('d')!=date('t')) {
            return ExitCode::OK;
        }
        
        $organizationalUnits = OrganizationalUnit::find()
            ->active()
            ->withPossibileActions(OrganizationalUnit::HAS_OWN_CASH)
            ->temporary(false)
            ->select(['id'])
            ->column()
        ;

        $begin = mktime(0, 0, 0, getdate()['mday']>15 ? getdate()['mon']+1: getdate()['mon'], 1, getdate()['year']);
                      
        $attributes = [
            'begin_date' => date('Y-m-d', $begin), // first day of month
            'end_date' => date('Y-m-d', mktime(0, 0, 0, getdate()['mday']>15 ? getdate()['mon']+2: getdate()['mon']+1, 0, getdate()['year'])), // last day of month
            'due_date' => date('Y-m-d', mktime(0, 0, 0, getdate()['mday']>15 ? getdate()['mon']+2: getdate()['mon']+1, 5, getdate()['year'])), // five days after end of month
            'name' => Yii::t('app', 'Periodical Report') . ' - ' . Yii::t('app', date('F', $begin)) . ' ' . date('Y', $begin),
            'required_attachments' => Yii::$app->params['periodicalReports']['requiredAttachments']
        ];

        $data=[];

        $existingPRs = PeriodicalReport::find()
            ->withBeginDate($attributes['begin_date'])
            ->withEndDate($attributes['end_date'])
            ->select(['organizational_unit_id'])
            ->column()
        ;

        foreach($organizationalUnits as $id) {
            
            if (in_array($id, $existingPRs)) {
                continue;
            }
            
            try {
                $periodicalReport = new PeriodicalReport();
                foreach($attributes as $key=>$value) {
                    $periodicalReport->$key = $value;
                }
                $periodicalReport->organizational_unit_id = $id;
                $periodicalReport->detachBehavior('fileBehavior');
                $periodicalReport->save();
                $data[] = [$id=>$periodicalReport->id];
            }
            catch (Exception $e) {
                echo $e->getMessage();
                return ExitCode::SOFTWARE;
            }
        }
        
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        return ExitCode::OK;

    }
    
    public function actionSendDigitalReceipts($secondsToWait=3600)
    {
        $receipts = DigitalReceipt::find()
            ->justIssued()
            ->createdBefore(time() - $secondsToWait)
            ->all()
            ;
        
        $data = ['sent'=>[], 'unsent'=>[]];
        
        foreach($receipts as $receipt) {
            $receipt->detachBehavior('fileBehavior');
            $receipt->fixContacts();
            if ($receipt->send()) {
                $receipt->sendToStatus('sent');
                $receipt->save(false);
                $data['sent'][] = $receipt->id;
            }
            else {
                $data['unsent'][] = $receipt->id;
            }
        }
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        return ExitCode::OK;
    }
    
    public function actionJournalizeDigitalReceipts($date=null)
    {  
        if (!$date) {
            $date = date('Y-m-d', time() - 24*60*60);
        }
        echo $date . "\n";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("

SELECT
    `organizational_units`.`id` as `organizational_unit_id`,
    `digital_receipts`.`digital_receipt_type_id`,
    `payment_method`,
    `digital_receipts`.`id` as `digital_receipt_id`,
    `digital_receipts`.`parent_id` as `digital_receipt_parent_id`,
    SUM(`amount`) as `lines_amount`,
    SUM(`digital_receipt_lines`.`amount` +  `digital_receipt_lines`.`discount`) as `lines_gross_amount`,
    SUM(`digital_receipt_lines`.`discount`) AS `lines_discount`,
    `sales_account_id`,
    `discounts_account_id`,
    `returns_account_id`
    
    FROM `digital_receipt_lines`
    JOIN `digital_receipts` ON `digital_receipts`.`id` = `digital_receipt_lines`.`digital_receipt_id`
    JOIN `organizational_units` ON `digital_receipts`.`organizational_unit_id` = `organizational_units`.`id`
    JOIN `products` on `digital_receipt_lines`.`product_id` = `products`.`id`

WHERE
    `date` = :date
    AND `voided_receipt_assigned_id` IS NULL
    AND `digital_receipts`.`wf_status` = 'DigitalReceiptWorkflow/sent'
    AND `digital_receipts`.`transaction_id` IS NULL
    AND `organizational_units`.`possible_actions` & 1 = 1

GROUP BY
    `organizational_units`.`id`,
    `digital_receipts`.`id`,
    `payment_method`,
    `sales_account_id`,
    `discounts_account_id`,
    `returns_account_id`

ORDER BY
    `organizational_units`.`id`,
    `payment_method`,
    `digital_receipts`.`id`;

        ", [':date' => $date]);

        $receipts = $command->queryAll();
        
        print_r($receipts);
        
        $transactions = [];

        foreach ($receipts as $row) {
            // Create a unique key based on the grouping criteria
            $key =  $row['organizational_unit_id'] . '|' . 
                    $row['digital_receipt_type_id'] . '|' . 
                    $row['payment_method'] . '|' .
                    $row['digital_receipt_parent_id']
                    ;
            
            // Initialize the group if it doesn't exist
            if (!isset($transactions[$key])) {
                $transactions[$key] = [
                    'organizational_unit_id' => $row['organizational_unit_id'],
                    'digital_receipt_type_id' => $row['digital_receipt_type_id'],
                    'payment_method' => $row['payment_method'],
                    'digital_receipt_parent_id' => $row['digital_receipt_parent_id'],
                    'lines_amount' => 0,
                    'lines_gross_amounts' => [],
                    'lines_discounts' => [],
                    'lines_returns' => [],
                    'digital_receipt_ids' => [],
                ];
            }
            
            // Sum the amounts
            $transactions[$key]['lines_amount'] += (float)$row['lines_amount'];
            $transactions[$key]['digital_receipt_ids'][] = $row['digital_receipt_id'];
            if (!$row['digital_receipt_parent_id']) { // ordinary sales receipt

                if (!isset($transactions[$key]['lines_gross_amounts'][$row['sales_account_id']])){
                    $transactions[$key]['lines_gross_amounts'][$row['sales_account_id']]=0;
                }
                $transactions[$key]['lines_gross_amounts'][$row['sales_account_id']] += (float)$row['lines_gross_amount'];

                if (!isset($transactions[$key]['lines_discounts'][$row['discounts_account_id']])){
                    $transactions[$key]['lines_discounts'][$row['discounts_account_id']]=0;
                }
                $transactions[$key]['lines_discounts'][$row['discounts_account_id']] += (float)$row['lines_discount'];

            }
            else {  // return receipt
                if (!isset($transactions[$key]['lines_returns'][$row['returns_account_id']])){
                    $transactions[$key]['lines_returns'][$row['returns_account_id']]=0;
                }
                $transactions[$key]['lines_returns'][$row['returns_account_id']] += (float)$row['lines_gross_amount'] - (float)$row['lines_discount'];
            }
        }

        // Reset array keys to get a clean indexed array
        $transactions = array_values($transactions);
        
        print_r($transactions);
        
        $data = ['transactions'=>[]];

        foreach($transactions as $transaction) {
            $data['transactions'][] = $this->journalize($transaction, $date);
        }
        
        echo json_encode($data, JSON_PRETTY_PRINT). "\n";
        
        return ExitCode::OK;
    }

    public function actionUpdateWorkflowStatuses()
    {  
        $receipts = DigitalReceipt::find()->justSent()->linkedToATransaction()->all();
        
        $data = ['updated'=>[]];
        
        foreach($receipts as $receipt) {
            $receipt->detachBehavior('fileBehavior');
            $receipt->sendToStatus('journalized');
            $receipt->save(false);
            $data['updated'][]=$receipt->id;
        }
      
        echo json_encode($data, JSON_PRETTY_PRINT). "\n";
        return ExitCode::OK;
    }

    private function journalize($td, $date) {
        
        $firstDigitalReceipt = DigitalReceipt::findOne($td['digital_receipt_ids'][0]);

        $coeff = 1;
        
        if ($td['digital_receipt_parent_id']) {
            $coeff = -1;
        }
        
        $pr = null; // periodical report
        
        $e = $firstDigitalReceipt->expo;
        if ($e && $e->periodical_report_id) {
            $pr = $e->periodicalReport;
        }
        else {
            $ou = OrganizationalUnit::findOne($td['organizational_unit_id']);
            if ($ou) {
                $pr = $ou->getPeriodicalReportByDate($date);
            }
        }
        if (!$pr) {
            return false;
        }

        $tt = TransactionTemplate::find()->withRank(-1)->one();
        if (!$tt) {
            return false;
        }
        
        $dbTransaction = Yii::$app->db->beginTransaction();
        
        try {

            $transaction = new Transaction();
            $transaction->detachBehavior('fileBehavior');
            $transaction->date = $date;
            $transaction->periodical_report_id = $pr->id;
            $transaction->transaction_template_id = $tt->id;
            $transaction->expo_id = $firstDigitalReceipt->expo_id;
            $transaction->user_id = -1; // System account
            $transaction->description = Yii::t('app', 'Daily {type} Receipts Recap', ['type' => $firstDigitalReceipt->getPaymentMethod(true)]);
            $transaction->save(false);

            // payment or reimbursement
            // the reimbusement by cash is already taken care of by the DigitaReceipt->adjustQuantities() method
            
            $paymentAccountId = Yii::$app->params['receipts']['payment_accounts'][$firstDigitalReceipt->getPaymentMethod(false)];
                        
            $this->createPosting(
                $transaction->id,
                $paymentAccountId,
                $td['lines_amount'] * $coeff
            );

            foreach($td['lines_gross_amounts'] as $account => $amount) {
                $this->createPosting(
                    $transaction->id,
                    $account,
                    - $amount
                );
            }

            foreach($td['lines_discounts'] as $account => $amount) {
                if ($amount) {
                    $this->createPosting(
                        $transaction->id,
                        $account,
                        $amount
                    );
                }
            }

            foreach($td['lines_returns'] as $account => $amount) {
                if ($amount) {
                    $this->createPosting(
                        $transaction->id,
                        $account,
                        $amount
                    );
                }
            }

            $transaction->sendToStatus('generated');
            $transaction->save(false);
                     
            foreach($td['digital_receipt_ids'] as $dr_id) {
                $receipt = DigitalReceipt::findOne($dr_id);
                $receipt->detachBehavior('fileBehavior');
                $receipt->transaction_id = $transaction->id;
                $receipt->sendToStatus('journalized');
                $receipt->save(false);
            }
        
            $dbTransaction->commit();
            return $transaction->id;
        }
        
        catch (\Exception $e) {
            // In case of a crash, rollback everything
            print_r($e);
            $dbTransaction->rollBack();
            throw $e;
        }
        
    }

    private function createPosting($transaction_id, $account_id, $amount)
    {
        $posting = new Posting();
        $posting->transaction_id = $transaction_id;
        $posting->account_id = $account_id;
        $posting->amount = $amount;
        return $posting->save();
    }
    
}

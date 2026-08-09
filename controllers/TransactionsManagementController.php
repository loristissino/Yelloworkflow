<?php

namespace app\controllers;

use Yii;
use app\models\Account;
use app\models\Transaction;
use app\models\TransactionForm;
use app\models\TransactionSearch;
use app\models\TransactionTemplate;
use app\models\Posting;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\CController;

/**
 * TransactionsManagementController implements the CRUD actions for Transaction model.
 */
class TransactionsManagementController extends CController
{
    
    public $periodicalReport;
    
    public function init()
    {
        parent::init();
        $this->viewPath = '@app/views';
        // This is needed because we want to use the same views for both
        // submitter and manager, that use different controllers
    }

    public function beforeAction($action)
	{
		$this->modelClass = Transaction::className();

		if (!parent::beforeAction($action)) {
			return false;
		}

		return true; // or false to not run the action
	}

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) // Displays a specific transaction
    {
        return $this->render('/transactions/view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Raw edit of a transaction and its postings.
     * * @param int $id The transaction ID
     */
    public function actionPatch($id)
    {
        $transaction = $this->findModel($id);
        
        $postingsText = "";
        if (Yii::$app->request->isGet) {
            foreach ($transaction->postings as $posting) {
                $postingsText .= $posting->account_id . "\t" . $posting->amount . "\n";
            }
        }

        $previewOutput = null; // Holds the formatted string for the view

        $reason = '';

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $transaction->description = $postData['description'] ?? '';
            $reason = $postData['reason'] ?? '';
            $postingsText = $postData['postings_text'] ?? '';
            $isPreview = (isset($postData['submit_type']) && $postData['submit_type'] === 'preview');
            
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                $success = true;
                if (!$transaction->save()) { $success = false; }
                
                $divider = str_repeat("-", 75);
                
                if ($success) {
                    Posting::deleteAll(['transaction_id' => $transaction->id]);
                    
                    // Set up the plaintext header arrays if we are previewing
                    if ($isPreview) {
                        $header = sprintf("%-5s  %-40s  %12s  %12s", "Id", "Account Name", "Debit", "Credit");
                        
                        $plainTextLines = [$header, $divider];
                    }
                    
                    $lines = explode("\n", str_replace("\r", "", $postingsText));
                    
                    $totalDebit = 0;
                    $totalCredit = 0;

                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;
                        
                        $parts = explode("\t", $line);
                        if (count($parts) >= 2) {
                            $accountId = trim($parts[0]);
                            $amount = (float)trim($parts[1]);
                            
                            $posting = new Posting();
                            $posting->transaction_id = $transaction->id;
                            $posting->account_id = $accountId;
                            $posting->amount = $amount;
                            
                            if (!$posting->save()) {
                                $success = false;
                                Yii::$app->session->setFlash('error', "Error saving posting: " . implode(', ', $posting->getFirstErrors()));
                                break;
                            }
                            
                            // Collect formatting lines on the fly during validation loop
                            if ($isPreview) {
                                
                                $account = Account::findOne($accountId);
                                $accountName = $account ? $account->name : "Unknown Account";
                                
                                $codeClean = substr($accountId, 0, 5);
                                $nameClean = substr($accountName, 0, 50);
                                
                                $debit = $amount > 0 ? number_format($amount, 2) : '';
                                $credit = $amount < 0 ? number_format(abs($amount), 2) : '';
                                if ($amount>0){
                                    $totalDebit += $amount;
                                }
                                else {
                                    $totalCredit -= $amount;
                                }
                                
                                $plainTextLines[] = sprintf("%-5s  %-40s  %12s  %12s", $codeClean, $nameClean, $debit, $credit);
                            }
                            
                        } else {
                            $success = false;
                            Yii::$app->session->setFlash('error', "Invalid line format: '$line'");
                            break;
                        }
                    }
                    $plainTextLines[] = $divider;
                    $plainTextLines[] = sprintf("%-48s %12s  %12s", '', number_format($totalDebit, 2), number_format($totalCredit,2));
                    
                }
                
                if ($success) {
                    if ($isPreview) {
                        // Force rollback so database remains completely untouched
                        $dbTransaction->rollBack();
                        
                        Yii::$app->session->setFlash('info', Yii::t('app', '<strong>Preview Generated:</strong> Please verify the entry below before saving.'));
                        $previewOutput = implode("\n", $plainTextLines);
                        
                        // Do NOT redirect. Fall through to render the form with $previewOutput.
                    } else {
                        $dbTransaction->commit();
                        Yii::$app->session->setFlash('success', 'Transaction updated successfully.');
                        \app\components\LogHelper::log('patched', $transaction, ['excluded'=>['created_at', 'updated_at'], 'change_description'=>$reason]);
                        return $this->redirect(['view', 'id' => $transaction->id]);
                    }
                } else {
                    $dbTransaction->rollBack();
                }
            } catch (\Exception $e) {
                $dbTransaction->rollBack();
                Yii::$app->session->setFlash('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        return $this->render('transactions/patch', [
            'transaction' => $transaction,
            'reason' => $reason,
            'postingsText' => $postingsText,
            'previewOutput' => $previewOutput, // Sent directly back to form view
        ]);
    }

    public function actionChange($id, $status) // Changes the workflow status of a transaction
    {
        $model = $this->findModel($id, false);
        $redirect = null;
        if ($status == 'TransactionWorkflow/recorded') {
            $redirect = ['periodical-reports-management/view', 'id'=>$model->periodicalReport->id];
        }
        return $this->_changeWorkflowStatus($model, $status, $redirect);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    protected function findPeriodicalReport($id)
    {
        if (($this->periodicalReport = \app\models\PeriodicalReport::find()
            ->withId($id)->withOrganizationalUnitId(Yii::$app->session->get('organizational_unit_id'))
            ->draft(true)
            ->one()) !== null) {
            return $this->periodicalReport;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
}

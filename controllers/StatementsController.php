<?php

namespace app\controllers;

use Yii;
use app\models\Account;
use app\models\AccountSearch;
use app\models\Posting;
use app\models\PostingSearch;
use app\models\PeriodicalReport;
use app\models\PeriodicalReportSearch;
use app\models\Transaction;
use yii\web\NotFoundHttpException;
use app\components\CController;
use yii\data\SqlDataProvider;

/**
 * StatementsController.
 */
class StatementsController extends CController
{

    use SubmissionsTrait;
    
    public function beforeAction($action)
    {
        if (($action->id == 'balance' and !Yii::$app->user->hasAuthorizationFor('transactions-management')) || $action->id != 'balance') {
            $this->setOrganizationalUnit($action);
            if (!$this->organizationalUnit) {
                $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
                return;
            }
        }
        
        return parent::beforeAction($action);
    }


    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex($year=null, $before=null) // Lists all available ledgers for the available accounts
    {
        if (!$year) {
            $redirect = ['index', 'year'=>date('Y')];
            if ($before) {
                $redirect['before']=$before;
            }
            return $this->redirect($redirect);
        }

        return $this->render('index', [
            'dataProviderForRealAccounts' => Account::getBalancesDataProviderForRealAccounts($this->organizationalUnit->id, null, $before),
            'dataProviderForTemporaryAccounts' => Account::getBalancesDataProviderForTemporaryAccounts($this->organizationalUnit->id, $year),
            'year' => $year,
        ]);
    }

    /**
     * Displays the Statement for a single Account model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $year=null) // Displays a ledger for a specific account of the organizational unit of the logged-in user
    {
        $postingSearchModel = new PostingSearch();
        
        $query = Posting::find()->orderBy('date')->select('postings.*, transactions.*')->withAccountId($id)->joinWith('periodicalReports')->withOrganizationalUnitId($this->organizationalUnit->id)->notWithTransactionStatus('TransactionWorkflow/rejected');
        
        if ($year) {
            $query = $query->inYear($year);
        }
        
        $postingDataProvider = $postingSearchModel->search(Yii::$app->request->queryParams, $query);
        
        $postingDataProvider->pagination = [
            'pageSize' => 1000,
        ];
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'postingDataProvider'=>$postingDataProvider,
        ]);
    }

    public function actionLedger($account, $ou) // Displays a ledger for a specific account of a specific organizational unit
    {
        $model = $this->findModel($account);
        
        $orgUnit = $this->findOU($ou); 
        
        $postingSearchModel = new PostingSearch();
        
        $weight = Yii::$app->user->identity->getPreference('transaction_statuses', 768);
        
        $query = Posting::find()->orderBy('date')->select('postings.*, transactions.*')->withAccountId($model->id)->joinWith('periodicalReports')->withOrganizationalUnitId($ou)->withOneOfTransactionStatuses($weight);
        
        $postingDataProvider = $postingSearchModel->search(Yii::$app->request->queryParams, $query);
        
        $postingDataProvider->pagination = [
            'pageSize' => 1000,
        ];
        
        return $this->render('ledger', [
            'model' => $model,
            'ou' => $orgUnit,
            'postingDataProvider'=>$postingDataProvider,
        ]);
    }
    
    public function actionBalance($id)
    {
        $periodicalReport = $this->findPeriodicalReport($id);
        
        return $this->render('balance', [
            'dataProvider' => Transaction::getBalance($periodicalReport),
            'periodicalReport' => $periodicalReport,
            'ou' => $this->findOu($periodicalReport->organizational_unit_id),
        ]);
    }
    
    public function actionSales($id, $view='lines')  // Displays the sales report linked to a periodical report
    {
        $periodicalReport = $this->findPeriodicalReport($id);

        $sql = "SELECT `date`, `accounts`.`name`, -sum(`amount`) as `amount` FROM `postings` LEFT JOIN `accounts` ON `postings`.`account_id` = `accounts`.id LEFT JOIN `transactions` ON `postings`.`transaction_id` = `transactions`.`id` LEFT JOIN `periodical_reports` ON `transactions`.`periodical_report_id` = `periodical_reports`.`id` WHERE `periodical_reports`.`id`=:id AND `accounts`.`represents`='S' GROUP BY `date`, `accounts`.`name`";
         
        // see https://www.yiiframework.com/doc/api/2.0/yii-data-sqldataprovider
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':id' => $id],
            'sort' => [
                'attributes' => [
                    'date',
                    'name',
                    'amount',
                    ],
                ],
            'pagination' => false,
            ]
        );
        
        return $this->render('sales', [
            'model' => $periodicalReport,
            'dataProvider'=>$dataProvider,
            'view'=>$view,
        ]);

    }
    

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    protected function findOU($id)
    {
        if (($model = \app\models\OrganizationalUnit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    protected function findPeriodicalReport($id)
    {
        $model = PeriodicalReport::findOne($id);
        
        if (Yii::$app->user->hasAuthorizationFor('transactions-management') or $model->organizationalUnit->id == $this->organizationalUnit->id) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }    
    
}

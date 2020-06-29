<?php

namespace app\controllers;

use Yii;
use app\models\Account;
use app\models\AccountSearch;
use app\models\Posting;
use app\models\PostingSearch;
use app\models\PeriodicalReport;
use app\models\PeriodicalReportSearch;
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
        $this->setOrganizationalUnit($action);
        if (!$this->organizationalUnit) {
            $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
            return;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays the Statement for a single Account model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        
        $postingSearchModel = new PostingSearch();
        
        $query = Posting::find()->orderBy('date')->select('postings.*, transactions.*')->withAccountId($id)->joinWith('periodicalReports')->withOrganizationalUnitId($this->organizationalUnit->id);
        
        $postingDataProvider = $postingSearchModel->search(Yii::$app->request->queryParams, $query);
        
        $postingDataProvider->pagination = [
            'pageSize' => 1000,
        ];
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'postingDataProvider'=>$postingDataProvider,
        ]);
    }

    public function actionLedger($account, $ou)
    {
        $model = $this->findModel($account);
        
        $orgUnit = $this->findOU($ou); 
        
        $postingSearchModel = new PostingSearch();
        
        $query = Posting::find()->orderBy('date')->select('postings.*, transactions.*')->withAccountId($model->id)->joinWith('periodicalReports')->withOrganizationalUnitId($ou);
        
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
    
    
    public function actionSales($id, $view='lines')  // periodical Report id
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
        
        if ($model->organizationalUnit->id == $this->organizationalUnit->id or Yii::$app->user->hasAuthorizationFor('transactions-management')) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }    
    
}

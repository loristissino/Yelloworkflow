<?php

namespace app\controllers\api\v1;

use yii\data\ActiveDataProvider;
use app\components\RestController;
use yii\web\ServerErrorHttpException;

class SystemController extends RestController
{

	public $modelClass = '\app\models\TransactionResource';

	public function actions()
	{
		$actions = parent::actions();
        
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

		return $actions;
	}
    
    public function actionIndex()
    {
        // call with curl -H 'X-API-KEY: ...' https://example.com/api/v1/system

        $this->checkAuth('system', 'index');
        return [
            'projects'=>\app\models\Project::find()->count(),
            'projectComments'=>\app\models\ProjectComment::find()->count(),
            'plannedExpenses'=>\app\models\PlannedExpense::find()->count(),
            'transactions'=>\app\models\Transaction::find()->count(),
            'postings'=>\app\models\Posting::find()->count(),
            'activities'=>\app\models\Activity::find()->count(),
            'notifications'=>\app\models\Notification::find()->count(),
            'periodicalReports'=>\app\models\PeriodicalReport::find()->count(),
        ];
    }    

    /*
    public function actionDelete()
    {
        // call with curl -H 'X-API-KEY: ...' -X DELETE https://example.com/api/v1/system/delete
        
        $this->checkAuth('system', 'delete');
        
        try {
            \app\models\Notification::deleteAll();
            \app\models\Activity::deleteAll();
            \app\models\Posting::deleteAll();
            \app\models\Transaction::deleteAll();
            \app\models\PlannedExpense::deleteAll();
            \app\models\ProjectComment::deleteAll();
            \app\models\Project::deleteAll();
            \app\models\PeriodicalReport::deleteAll();
            return ['result'=>'deleted'];
        }
        catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    */
    
    public function actionCreate()
    {
        $this->checkAuth('system', 'create');
        
        $year = date('Y');
        $month = date('m');
        
        $OUs = \app\models\OrganizationalUnit::find()->active()->withPossibileActions(\app\models\OrganizationalUnit::HAS_OWN_CASH)->all();
        
        $locale = setlocale(LC_TIME, str_replace('-', '_', \Yii\helpers\ArrayHelper::getValue($_SERVER, 'HTTP_ACCEPT_LANGUAGE', 'en_US')).'.UTF8');
        \Yii::$app->language = substr($locale, 0, 2);

        $count = 0;
        foreach ($OUs as $ou) {
            for ($m=1; $m<=$month+1; $m++) {
                $this->_createPeriodicalReport(
                    $ou,
                    strftime("%B %Y", mktime(0, 0, 0, $m, 1, $year)),
                    date('Y-m-d', mktime(0,0,0, $m, 1, $year)),
                    date('Y-m-d', mktime(0,0,0, $m+1, 1, $year)-60*60*24)
                );
                $count++;
            }
            $this->_createPeriodicalReport(
                $ou,
                \Yii::t('app', 'Initial balances'),
                date('Y-m-d', mktime(0,0,0, 1, 1, $year)),
                date('Y-m-d', mktime(0,0,0, 1, 1, $year))
            );
        }
        
        return ['created' => $count];
    }
    
    private function _createPeriodicalReport($ou, $description, $begin_date, $end_date)
    {
        $periodicalReport = new \app\models\PeriodicalReport();
        $periodicalReport->begin_date = $begin_date;
        $periodicalReport->end_date= $end_date;
        $periodicalReport->organizational_unit_id = $ou->id;
        $periodicalReport->name = \Yii::t('app', 'Periodical Report') . ' - ' . $description;
        $periodicalReport->save();
    }
    
}



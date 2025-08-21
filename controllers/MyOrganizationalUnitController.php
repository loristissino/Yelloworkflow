<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\CController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\models\Affiliation;
use app\models\ViewedOuMainActivity;
use app\models\ViewedOuMainActivitySearch;

/**
 * MyOrganizationalUnitController allows users to chech who belongs to their Organizational Unit.
 */
class MyOrganizationalUnitController extends CController
{

    use SubmissionsTrait;

    public function init()
    {
        parent::init();
        $this->viewPath = '@app/views';
        // This is needed because we want to use the views in the 'site' folder
    }

    public function beforeAction($action)
    {
        if (!in_array($action->id, [])) {
            $this->setOrganizationalUnit($action);
            if (!$this->organizationalUnit) {
                $this->redirect(['/site/choose-organizational-unit', 'return'=>\Yii::$app->request->url]);
                return;
            }
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex($daysBack=365)
    {
        $query = ViewedOuMainActivity::find()
            ->andFilterWhere([
                'organizational_unit_id' => $this->organizationalUnit->id,
            ])
            ->andFilterWhere(['>=', 'happened_at', time() - $daysBack*24*60*60]);
        
        $activitiesDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'happened_at' => SORT_DESC,
                ],
            ],
        ]);
        
        return $this->render('site/my-organizational-unit', [
            'peopleDataProvider' => new ActiveDataProvider([
                'query' => Affiliation::find()->withOrganizationalUnit($this->organizationalUnit)->joinWith('role')->orderBy(['roles.rank'=>SORT_ASC]),
            ]),
            'activitiesDataProvider' => $activitiesDataProvider,
            'ou' => $this->organizationalUnit,
        ]);
    }

}

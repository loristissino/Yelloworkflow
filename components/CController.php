<?php

namespace app\components;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Activity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AuthorizationFilter;
use app\controllers\ControllerTrait;

/**
 * CController extends the standard Controller.
 */
class CController extends Controller
{
    use ControllerTrait;
    
    public $modelClass;
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AuthorizationFilter::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'clone' => ['POST'],
                ],
            ],
        ];
    }

    public function notSavingBulkActions() {
        return ['delete'];
    }

    public function actionClone($id)
    {
        $model = $this->findModel($id)->cloneModel();
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    public function actionLog($id)
    {
        $model = $this->findModel($id, false);
        $query = Activity::find()->withModel(get_class($model))->withModelId($id);

        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'happened_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('/activities/model_log', [
            'searchModel' => null,
            'dataProvider' => $provider,
            'model' => $model,
        ]);
    }
    
    public function actionProcess($action='', $redirect=null) {
        // $action = Yii::$app->request->post('action'); // dropDown (array)
        if (!$action) {
            $action = Yii::$app->request->post('action'); // kept for the dropDown use
        }
        
        $selection = Yii::$app->request->post('selection'); //checkbox (array)
        
        $bulkprocess_id = Yii::$app->request->post('bulkprocess_id');
        
        if (!$action) {
            Yii::$app->session->setFlash('warning', Yii::t('app', "Action not specified."));
        }
        elseif (!$selection) {
            Yii::$app->session->setFlash('warning', Yii::t('app', "No items selected."));
        }
        else {
            $flashSuccess = '';
            $flashFailed = '';
            $message = $this->modelClass::getBulkActionMessage($action);
            if ($message) {
                $successCount = 0;
                $failedCount = 0;
                foreach ($this->modelClass::findAll($selection) as $model) {
                    
                    $r = $model->$action($bulkprocess_id);
                    
                    if (!in_array($action, $this->notSavingBulkActions())) {
                        $r = $r and ($model->save());
                    }
                    
                    if ($r)
                    {
                        $successCount++;
                    }
                    else 
                    {
                        $failedCount++;
                    }
                }
                if ($successCount>0)
                {
                    $flashSuccess = Yii::t('app', $message, ['count'=>$successCount]);
                }
                if ($failedCount>0)
                {
                    $flashFailed = Yii::t('app', 'The request failed for {count,plural,=0{no items} =1{one item} other{# items}}.', ['count'=>$failedCount]);
                }
            }
            else {
                $flashFailed = "Not a recognized action";
            }
            if ($flashSuccess) {
                Yii::$app->session->setFlash('success', $flashSuccess);
            }
            if ($flashFailed) {
                Yii::$app->session->setFlash('warning', $flashFailed);
            }
        }
        
        if (!$redirect) {
            $redirect = ['index'];
        }
        
        return $this->redirect($redirect);
    }
    
    protected function _changeWorkflowStatus($model, $status, $redirect=null)
    {
        if ($model->sendToStatus($status)) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Status changed to {name}.', ['name'=>$status]));
        }
        else {
            Yii::$app->session->setFlash('error', Yii::t('app', $model->workflowError));
        }
        if (!$redirect) {
            $redirect = ['view', 'id' => $model->id];
        }
        return $this->redirect($redirect);
    }


    
}

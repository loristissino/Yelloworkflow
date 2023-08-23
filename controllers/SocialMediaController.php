<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;
use app\components\CController;
use app\models\sm\MastodonSchedulerClient;
use app\models\sm\MastodonPostForm;
use app\components\LogHelper;

/**
 * PetitionsController implements the CRUD actions for Petition model.
 */
class SocialMediaController extends CController
{

    public function beforeAction($action)
    {            

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    public function actionMastodon($action='index', $id=null)
    {
        $mastodon = new MastodonSchedulerClient(Yii::$app->params['sm']['mastodon']);
        $model = new MastodonPostForm($mastodon);
        
        //die($action);
        switch($action) {
            case 'delete':
                $this->_enforcePost();
                $mastodon->delete($id);
                return $this->redirect(['mastodon', 'action'=>'index']);
            case 'schedule':
                $this->_enforcePost();
                if ($model->load(Yii::$app->request->post()) && $model->schedule()) {
                    LogHelper::log('scheduled', $model, ['excluded'=>['id', 'created_at', 'attachments']]);
                    return $this->redirect(['mastodon', 'action'=>'index']);
                }
                break;
        }
        
        return $this->render('mastodon', [
            'action' => $action,
            'mastodon' => $mastodon,
            'model' => $model,
        ]);
    }
    
    private function _enforcePost()
    {
        if (!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException(Yii::t('app', 'Method not allowed.'));
        }
    }
    

}

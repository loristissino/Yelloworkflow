<?php

namespace app\controllers;

use Yii;
use app\models\Petition;
use app\models\PetitionSignature;
use app\models\PetitionSearch;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\components\CController;

/**
 * PetitionsController implements the CRUD actions for Petition model.
 */
class PetitionsController extends CController
{

    public function beforeAction($action)
    {            
        if (in_array($action->id, ['sign'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Petition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PetitionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionActivePetitions($key)
    {
        // specifically authorized for anonymous users in the database table authorizations
        $this->_checkKey($key);
        $this->layout = false;
        $petitions = Petition::getActivePetitions();
        return $this->render('active-petitions', [
            'petitions' => $petitions,
        ]);
    }

    public function actionPetitionText($key, $slug)
    {
        // specifically authorized for anonymous users in the database table authorizations
        $this->_checkKey($key);
        $this->layout = false;
        $model = Petition::find()->withSlug($slug)->one();
        if ($model) {
            return $this->render('petition', [
                'model' => $model,
                'image_html_template' => Yii::$app->params['petitions'][$key]['image_html_template'],
            ]);
        }
        else
            return 'invalid slug';
    }

    public function actionPetitionSignatures($key, $slug, $limit=100, $offset=0, $orderedBy='created_at/DESC', $messages='true', $lastnames='true')
    {
        // specifically authorized for anonymous users in the database table authorizations
        $this->_checkKey($key);
        $this->layout = false;
        $model = Petition::find()->withSlug($slug)->one();
        
        list($key, $value) = explode('/', $orderedBy);
        $orderBy=[$key=>$value];
        
        if ($model) {
            $signatures = $model->getPetitionSignatures()->confirmed()->orderBy($orderBy)->limit($limit)->offset($offset)->all();
            return $this->render('signatures', [
                'model' => $model,
                'signatures' => $signatures,
                'options' => ['messages'=>$messages!='false', 'lastnames'=>$lastnames!='false'],
            ]);
        }
        else
            return 'invalid slug';
    }

    /**
     * Displays a single Petition model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Petition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     /*
    public function actionCreate()
    {
        $model = new Petition();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    */
    /**
     * Updates an existing Petition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Petition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    * */
    
    public function actionConfirm($key, $email, $slug, $code) {
        $this->_checkKey($key);
        $petition = $this->findActivePetitionBySlug($slug);
        $this->layout = false;

        return PetitionSignature::confirmSignature($petition->id, $email, $code);
    }
    
    public function actionSign($slug, $key, $values='')
    {
        // specifically authorized for anonymous users in the database table authorizations
        $this->_checkKey($key);

        $this->layout = false;
        $petition = $this->findActivePetitionBySlug($slug);
        
        $signature = new PetitionSignature();
        $signature->accepted_terms = '';
        $signature->agreed_to_keep_me_updated = '';
        $signature->agreed_to_allow_to_be_contacted = '';
        
        if ($values) {
            $data = $this->b64_unserialize($values);
            foreach([
                'first_name',
                'last_name',
                'email',
                'yob',
                'district',
                'gender',
                'message',
                'accepted_terms',
                'agreed_to_keep_me_updated',
                'agreed_to_allow_to_be_contacted',
            ] as $field) {
                if (isset($data[$field])) {
                    $signature->$field = $data[$field];
                }
            }
        }

        $signature->processingUrl = Yii::$app->params['petitions'][$key]['envolve_action'];
        $signature->petition_slug = $slug;
        $signature->petition_id = $petition->id;
        $signature->captcha = Yii::$app->params['petitions'][$key]['envolve_captcha'];
        
        if (Yii::$app->request->isPost) {
            
            $signature->generateConfirmationCode();
            
            $result = ['status'=>'maybe'];
            
            if ($signature->validate() && $signature->save() && $signature->prepareConfirmationEmail($key)) {
                $result['status']='ok';
                $result['id']=$signature->id;
            }
            else {
                $result['status']='failed';
                $errors = [];
                foreach($signature->getErrors() as $key=>$value) {
                    $v = is_array($value) ? join(';', $value) : $value;
                    if (strpos($v, 'Petition and Email')!==false) {
                        $v = Yii::t('app', 'This petition has already been signed with the email address provided.');
                    }
                    $errors[$key] = $v;
                }
                $result['errors']=$errors;
            }
            
            return serialize($result);
        }
        
        return $this->renderPartial('sign', [
                'model' => $signature,
                'petition'=>$petition,
                'key'=>$key,
                ]
            );
    }
    
    protected function b64_unserialize($value) {
        //return unserialize(base64_decode(str_replace('_', '/', $value)));
        return json_decode(base64_decode(str_replace('_', '/', $value)), true);
    }

    /**
     * Finds the Petition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Petition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Petition::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findActivePetitionBySlug($slug)
    {
        if (($model = Petition::find()->withSlug($slug)->active()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist.'));
    }
    
    protected function _checkKey($key)
    {
        if (!isset(Yii::$app->params['petitions'][$key])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }
    }

}

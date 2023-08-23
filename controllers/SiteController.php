<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\LoginForm;
use app\models\TokenForm;
use app\models\PwdResetForm;
use app\models\PwdChangeForm;
use app\models\IssuesForm;
use app\components\CController;
use app\models\Authorization;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use app\models\Apikey;

class SiteController extends CController
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() // Displays the home page
    {
        return $this->render('index');
    }
    
    public function actionPing()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest) {
            return '';
        }
        Yii::$app->user->identity->touchLastActionAt();
        $content['users'] = Yii::$app->user->identity->getOtherOnlineUsersAsProcessedArray();
        $content['unseen_notifications'] = Yii::$app->user->identity->getNumberOfUnseenNotifications();
        
        return $content;
    }
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() // Displays the login page
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (isset(Yii::$app->params['loginInfo'])) {
            Yii::$app->session->setFlash('info', Yii::$app->params['loginInfo']);
        }
        
        $model = new LoginForm();
        
        /*
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            \app\components\LogHelper::log('Login', $model->getUser(), ['excluded'=>[
                'first_name','last_name', 'email', 'auth_key', 'access_token','otp_secret','created_at', 'updated_at',
            ]]);
            return $this->_back();
        }
        */

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if ($model->needsToken) {
                Yii::$app->session->set('user2FA', $model);
                return $this->redirect(['site/token']);
            }
            
            $model->login();
            \app\components\LogHelper::log('Login', $model->getUser(), ['excluded'=>[
                'first_name','last_name', 'email', 'auth_key', 'access_token','otp_secret','created_at', 'updated_at',
            ]]);
            return $this->_back();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionToken() // Displays the token page
    {
        $loginForm = Yii::$app->session->get('user2FA');
        if (!$loginForm){
            throw new ForbiddenHttpException('Invalid session.');
        }
        
        $model = new TokenForm();
        $model->user = $loginForm->getUser();
        Yii::$app->session->setFlash('info', null);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $loginForm->login();
            
            $user = $loginForm->getUser();
            
            if ($model->trust_user_agent) {
                $user->trustUserAgent();
            }
            
            \app\components\LogHelper::log('Login', $user, ['excluded'=>[
                'first_name','last_name', 'email', 'auth_key', 'access_token','otp_secret','created_at', 'updated_at',
            ]]);
            return $this->_back();
        }

        return $this->render('token', [
            'model' => $model,
        ]);
    }
    
    public function actionEnableTwoFactorAuthentication()
    {
        $model = new TokenForm();
        $model->secret = Yii::$app->session->get('2FA_secret');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->user->identity->otp_secret =$model->secret;
            Yii::$app->user->identity->save();
            \app\components\LogHelper::log('2FA enabled', Yii::$app->user->identity, ['excluded'=>[
                'first_name','last_name', 'email', 'auth_key', 'access_token','otp_secret','created_at', 'updated_at',
            ]]);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Two-factor authentication enabled.'));
            return $this->redirect(['site/profile']);
        }

        return $this->render('two-factor-authentication', [
            'model' => $model,
        ]);
    }

    public function actionDisableTwoFactorAuthentication()
    {
        Yii::$app->user->identity->sendEmailToConfirm2FADisabling();
        return $this->redirect(['site/profile']);
    }

    public function actionDisableTfa($u, $c)
    {
        $id = $this->getIdFromEmail($u, $c);
        
        $user = User::findOne($id);
        if ($user) {
            $user->otp_secret = null;
            foreach ($user->getUserAgents()->all() as $ua){
                $ua->delete();
            }
            $user->save();
        }
        \app\components\LogHelper::log('2FA disabled', $user, ['excluded'=>[
            'first_name','last_name', 'email', 'auth_key', 'access_token','otp_secret','created_at', 'updated_at',
        ]]);

        Yii::$app->session->setFlash('success', Yii::t('app', 'Two-factor authentication disabled.'));
        return $this->redirect(['site/profile']);
    }

    public function actionPwdreset() // Displays the "Forgot your password?" page
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (isset(Yii::$app->params['loginEmailInfo'])) {
            Yii::$app->session->setFlash('info', Yii::$app->params['loginEmailInfo']);
        }

        $model = new PwdResetForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->sendEmail(false);
        }

        $model->username = '';
        return $this->render('pwdreset', [
            'model' => $model,
        ]);
    }

    public function actionPwd($u, $c) // Allows the password to be reset
    {
        $id = $this->getIdFromEmail($u, $c);
                
        $model = new PwdChangeForm();
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword($id)) {
            return $this->redirect(['site/login']);
        }

        $model->password = '';
        return $this->render('pwdchange', [
            'model' => $model,
        ]);
    }
    
    public function actionRevokeUa($id){
        if (Yii::$app->user->identity->revokeUA($id)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'User Agent revoked.'));
        }
        else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'The User Agent could not be revoked.'));
        }
        return $this->redirect(['site/profile']);
    }

    private function _back()
    {
        $return_url = Yii::$app->request->get('return');
        if ($return_url) {
            return $this->redirect($return_url);
        }
        return $this->goBack(['site/dashboard']);
    }
    
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() // Makes the user log out
    {
        if (!Yii::$app->user->isGuest) {
            \app\components\LogHelper::log('Logout', Yii::$app->user->identity, ['excluded'=>[
                'first_name','last_name', 'email', 'auth_key', 'access_token','otp_secret','created_at', 'updated_at',
            ]]);
            Yii::$app->user->identity->touchLastActionAt(true);
        }
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Handbook action.
     *
     * @return Response
     */
    public function actionHandbook() 
    {
        return $this->redirect(Yii::$app->params['handbookUrl']);
    }


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionIssues($reference, $url) // Displays the "Issues" page
    {
        $model = new IssuesForm();
        $model->reference = $reference;
        $model->url = urldecode($url);
        if ($model->load(Yii::$app->request->post()) && $model->notify()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Issue submitted.'));
            return $this->redirect($model->url);
        }
        return $this->render('issues', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() // Displays the "About" page
    {
        return $this->render('about');
    }

    public function actionProfile() // Displays the "Profile" page
    {
        Yii::$app->session->setFlash('info', null);

        $secret_key = '';
        if (!Yii::$app->user->identity->otp_secret) {
            $secret_key = \app\components\Google2FA::generate_secret_key();
            Yii::$app->session->set('2FA_secret', $secret_key);
        }
        
        return $this->render('profile', [
            'secret_key' => $secret_key
        ]);
    }
    
    public function actionDashboard() // Displays the "Dashboard" page
    {
        Yii::$app->session->setFlash('info', null);
        $controllers = Authorization::getAuthorizedControllers();
        
        return $this->render('dashboard', [
            'controllers'=>$controllers,
            ]
        );
    }

    public function actionChooseOrganizationalUnit($id=null, $return='') // Allows the user to switch to a different organizational unit they belong to
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login', 'return' => $return]);
        }
        $ous = Yii::$app->user->identity->getOrganizationalUnits()->all();
        $current_ou_id = Yii::$app->session->get('organizational_unit_id');
        
        if (sizeof($ous)==0) {
            throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
        }
        else if(sizeof($ous)==1) {
            Yii::$app->session->set('organizational_unit_id', $ous[0]->id);
            return $this->_back();
        }
        
        if (Yii::$app->request->isPost)
        {
            if (!in_array($id, ArrayHelper::map($ous, 'id', 'id'))) {
                throw new ForbiddenHttpException(Yii::t('app', 'Not authorized.'));
            }
            Yii::$app->session->set('organizational_unit_id', $id);
            if ($current_ou_id and $id!=$current_ou_id) {
                $ou = array_filter($ous, function ($x) use ($id) {return $x->id == $id; });
                $chosen = array_pop($ou);
                $name = $chosen->name;
                Yii::$app->session->setFlash('success', Yii::t('app', 'Organizational Unit set to «{organizational_unit_name}».', ['organizational_unit_name'=>$name]));
            }
            return $this->_back();
        }
        
        return $this->render('choose-organizational-unit', [
            'organizational_units'=>$ous,
            'return' => $return,
            ]
        );
    }

    public function actionApikey($action, $id=null) // Creates or deletes an API key [for REST services]
    {
        switch ($action) {
            case 'create':
                $apikey = new Apikey();
                $apikey->value = Apikey::generateRandomValue();
                $apikey->user_id = \Yii::$app->user->id;
                $apikey->app = 'Generic App';
                $apikey->save();
                Yii::$app->session->setFlash('success', Yii::t('app', 'API key generated'));
                break;
                
            case 'delete':
                Apikey::find()->ofUser(\Yii::$app->user)->withid($id)->one()->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'API key deleted'));
                break;
        }

        return $this->goBack(['site/profile']);

    }
    
    public function actionSetPreference($returnUrl='')
    {
        $model = new \app\models\TransactionStatusesForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Change applied.'));
            return $this->redirect($returnUrl);
        }
        print_r($model);
        die();
    }
    
    public function getIdFromEmail($u, $c)
    {
        if (strpos($u, '_')<1 or (md5($u . Yii::$app->params['notificationsKey'])!=$c)) {
            throw new ForbiddenHttpException('Wrong link.');
        }
        list($id, $timestamp) = explode('_', $u);
        if (time() > $timestamp) {
            throw new ForbiddenHttpException('Link expired.');
        }
        return $id;
    }
    
}

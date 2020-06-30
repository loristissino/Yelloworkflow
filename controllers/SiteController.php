<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->_back();
            /*
            $return_url = Yii::$app->request->get('return');
            if ($return_url) {
                return $this->redirect($return_url);
            }
            return $this->goBack(['site/dashboard']);
            */
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
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
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }
    
    public function actionDashboard()
    {
        $controllers = array_merge([
            'site/profile' => [
                'icon' => 'user',
                'color' => '#093609',
                'title' => 'Profile',
                'description' => 'About you',
            ],
        ], Authorization::getAuthorizedControllers());
        
        return $this->render('dashboard', [
            'controllers'=>$controllers,
            ]
        );
    }
    
    

    public function actionChooseOrganizationalUnit($id=null, $return='')
    {
        if (Yii::$app->user->isGuest) {
            return;
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

    public function actionApikey($action, $id=null)
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
}

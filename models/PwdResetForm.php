<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Markdown;
use yii\helpers\Url;
/**
 * PwdResetForm is the model behind the password reset form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PwdResetForm extends Model
{
    public $username;
    
    private $_user;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username or Email'),
        ];
    }
    
    public function sendEmail()
    {
        $user = $this->getUser();
        
        if ($user) {
                        
            $notification = new Notification();
            
            $u = sprintf('%d_%d', $user->id, time()+3600);
            $link = Url::to(['site/pwd', 'u'=>$u, 'c'=>md5($u . Yii::$app->params['notificationsKey'])], true);
            
            $notification->user_id = $user->id;
            $notification->subject = Yii::t('app', 'YWF Password Reset');
            $notification->plaintext_body = Yii::t('app', 'Your username is {username}.\nThis is the link to reset your password:\n{link}\n\n(The link expires in one hour.)', ['username'=>$user->username, 'link'=>$link]);
            $notification->html_body = Markdown::process(Yii::t('app', "Your username is **{username}**.\n\nTo reset your password, follow this [link]({link}). The link expires in one hour.", ['username'=>$user->username, 'link'=>$link]));
            //$notification->save();
            $notification->sendEmail();
            
            Yii::$app->session->setFlash('success', Yii::t('app', 'Email sent to {email}. The link expires in one hour. Check your spam folder.', ['email'=> \app\models\Notification::reduceEmailAddress($user->email)]));
            return true;
        }
        else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'User not found.'));
            return false;
        }
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        $this->_user = false;
        $this->_user = User::find()->active(true)->withUsername($this->username)->one();
        if (!$this->_user) {
            $this->_user = User::find()->active(true)->withEmail($this->username)->one();
        }

        return $this->_user;
    }    
    
}

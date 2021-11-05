<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * PwdChangeForm is the model behind the password change form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PwdChangeForm extends Model
{
    public $password;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'], 'string', 'min'=>10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
        ];
    }
    
    public function resetPassword($id)
    {
        $user = $this->getUser($id);
        
        if ($user) {
            
            $user->auth_key = $this->password;
            $user->encryptPassword();
            if ($user->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Password successfully reset.'));
                return true;
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'The password could not be reset.'));
                return false;
            }
        }
        else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'User not found.'));
            return false;
        }
    }
    
    public function getUser($id)
    {
        return User::find()->active(true)->withId($id)->one();
    }    
    
}

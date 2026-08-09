<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\Google2FA;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class TokenForm extends Model
{
    public $token;
    public $user;
    public $secret = null;
    public $trust_user_agent = false;
    public $sms = false;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            ['token', 'validateToken'],
            ['trust_user_agent', 'safe'],
        ];
    }

    public function validateToken($attribute, $params)
    {
        if ($this->sms) {
            $sms = Yii::$app->session->get('user2FA_sms', []);
            if (!$sms) {
                $this->addError($attribute, Yii::t('app', 'Invalid session.'));
            }
            if (time() > $sms['expiresAt']) {
                $this->addError($attribute, Yii::t('app', 'Expired code.'));
            }
            if ($sms['randomNumber']!=trim($this->token)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect code.'));
            }
            return;
        }
        
        $initializationKey = $this->secret ? $this->secret : $this->user->otp_secret;
        
        if (!Google2FA::verify_key($initializationKey, $this->token, 2)) {
            $this->addError($attribute, Yii::t('app', 'Incorrect token.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token' => Yii::t('app', 'Code'),
            'trust_user_agent' => Yii::t('app', 'Don\'t ask again for this device'),
        ];
    }
    
}

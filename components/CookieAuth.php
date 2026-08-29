<?php

namespace app\components;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\IdentityInterface;

class CookieAuth extends AuthMethod
{
    /**
     * @var string the name of the cookie that stores the identity.
     */
    public $cookieName = '_identity';

    /**
     * Authenticates the current user based on the cookie.
     */
    public function authenticate($user, $request, $response)
    {
        // getCookies()->getValue() automatically validates the MAC hash 
        // and unserializes the cookie, returning just the inner JSON string.
        $cookieValue = $request->cookies->getValue($this->cookieName);

        if ($cookieValue !== null) {
            $data = json_decode($cookieValue, true);

            // The data structure is typically: [id, auth_key, duration]
            if (is_array($data) && count($data) >= 2) {
                $id = $data[0];
                $authKey = $data[1];
                
                /* @var $identityClass IdentityInterface */
                $identityClass = $user->identityClass;
                
                $identity = $identityClass::findIdentity($id);
                
                if ($identity !== null && $identity->validateAuthKey($authKey)) {
                    // Logs the user in for the duration of this request only 
                    // (since parent init() sets enableSession = false)
                    $user->login($identity);
                    return $identity;
                }
            }
        }

        // Return null if authentication fails; AuthMethod will handle throwing an UnauthorizedHttpException
        return null;
    }
}

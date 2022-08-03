<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use \app\models\Apikey;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string|null $otp_secret
 * @property int $status
 * @property int|null $external_id
 * @property int|null $last_renewal
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $last_action_at
 *
 * @property Affiliation[] $affiliations
 * @property OrganizationalUnit[] $organizationalUnits
 * @property Apikey[] $apikeys
 * @property Authorization[] $authorizations
 * @property Activity[] $activities
 * @property Notification[] $notifications 
 * @property PeriodicalReportComment[] $periodicalReportComments
 * @property ProjectComment[] $projectComments
 * @property Transaction[] $transactions
 * @property UserAgent[] $userAgents
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    use ModelTrait;
    
    private $_doNotLog = false;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'last_name', 'email', 'auth_key'], 'required'],
            [['status', 'external_id', 'last_renewal', 'created_at', 'updated_at'], 'integer'],
            [['last_action_at'], 'safe'],
            [['username'], 'string', 'max' => 20],
            [['first_name', 'last_name'], 'string', 'max' => 40],
            [['email', 'auth_key'], 'string', 'min'=>4, 'max' => 100], // TODO The min limit is for debugging purposes
            [['access_token', 'otp_secret'], 'safe'],
            //[['access_token'], 'string', 'max' => 255],
            //[['otp_secret'], 'string', 'max' => 128],
            [['username'], 'unique'],
            [['email'], 'unique'],
	        [['external_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'auth_key' => Yii::t('app', 'Password'),
            'access_token' => Yii::t('app', 'Access Token'),
            'otp_secret' => Yii::t('app', 'Otp Secret'),
            'status' => Yii::t('app', 'Is Active?'),
            'external_id' => Yii::t('app', 'External Id'),
            'last_renewal' => Yii::t('app', 'Last Renewal'),
            'last_action_at' => Yii::t('app', 'Last Action At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getFullName()
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }
    
    public function getFullNameWithMembership()
    {
        $text = $this->getFullName();
        if (!$this->isMember) {
            $text .= ' ⚠️ ' .Yii::t('app', 'Membership not renewed.');
        }
        return $text;
    }
    
    public function getIsMember()
    {
        return $this->last_renewal == date('Y');
    }
    
    public function getUsesATrustedUserAgent()
    {
        $requestCookies = Yii::$app->request->cookies;
        
        $ua = $requestCookies->getValue('ywf_ua', '');
        
        $ua = $this->getUserAgents()->withHash($ua)->one();
        
        return $ua != null;
    }
    
    public function revokeUA($id)
    {
        $ua = $this->getUserAgents()->withId($id)->one();
        if ($ua) {
            $ua->delete();
            return true;
        }
        return false;
    }
    
    public function trustUserAgent()
    {
        if ($this->usesATrustedUserAgent) {
            return true;
        }
        
        $cookie = bin2hex(random_bytes(20));
        
        // get the cookie collection (yii\web\CookieCollection) from the "response" component
        $responseCookies = Yii::$app->response->cookies;

        // add a new cookie to the response to be sent
        $responseCookies->add(new \yii\web\Cookie([
            'name' => 'ywf_ua',
            'value' => $cookie,
            'expire' => time()+24*60*60*30,
        ]));
        
        $userAgent = new UserAgent();
        $userAgent->hash = $cookie;
        $userAgent->user_id = $this->id;
        $userAgent->info = Yii::$app->request->getUserAgent();
        return $userAgent->save();
    }

    /**
     * Gets query for [[Affiliations]].
     *
     * @return \yii\db\ActiveQuery|AffiliationQuery
     */
    public function getAffiliations()
    {
        return $this->hasMany(Affiliation::className(), ['user_id' => 'id']);
    }
    
    /**
     * Gets query for [[OrganizationalUnits]].
     *
     * @return \yii\db\ActiveQuery|OrganizationalUnitQuery
     */
    public function getOrganizationalUnits()
    {
        return $this
            ->hasMany(OrganizationalUnit::className(), ['id' => 'organizational_unit_id'])
            ->viaTable('affiliations', ['user_id' => 'id'])
            ->withPossibileActions(OrganizationalUnit::HAS_OWN_PROJECTS)
            ->active();
    }

    /**
     * Gets query for [[Apikeys]].
     *
     * @return \yii\db\ActiveQuery|ApikeyQuery
     */
    public function getApikeys()
    {
        return $this->hasMany(Apikey::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Authorizations]].
     *
     * @return \yii\db\ActiveQuery|AuthorizationQuery
     */
    public function getAuthorizations()
    {
        return $this->hasMany(Authorization::className(), ['user_id' => 'id'])->active();
    }

    /**
     * Gets query for [[Activities]].
     *
     * @return \yii\db\ActiveQuery|ActivityQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['user_id' => 'id']);
    }

    /**
    * Gets query for [[Notifications]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getNotifications() 
    { 
       return $this->hasMany(Notification::className(), ['user_id' => 'id']); 
    } 

    /** 
    * Gets query for [[PeriodicalReportComments]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getPeriodicalReportComments() 
    { 
       return $this->hasMany(PeriodicalReportComment::className(), ['user_id' => 'id']); 
    } 

    /**
     * Gets query for [[ProjectComments]].
     *
     * @return \yii\db\ActiveQuery|ProjectCommentQuery
     */
    public function getProjectComments()
    {
        return $this->hasMany(ProjectComment::className(), ['user_id' => 'id']);
    }

    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['id' => 'role_id'])->viaTable('affiliations', ['user_id' => 'id'])->active();
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery|TransactionQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserAgents]].
     *
     * @return \yii\db\ActiveQuery|UserAgentQuery
     */
    public function getUserAgents()
    {
        return $this->hasMany(UserAgent::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    
    public function getId()
    {
       return $this->id;
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //return self::find()->where(['accessToken' => $token])->one();
        return ApiKey::getUserByAccessToken($token);
    }
    
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->one();
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->getAuthKey());
    }
    
    public function fixPermissions()
    {
        $log = "inside fixPermissions() for user " . $this->username . "\n";
        
        $role_ids = [];
        foreach($this->getRoles()->all() as $role) {
            $role->addPermissionsForUser($this);
            $log .= 'Called addPermissions() for role ' . $role->name . "\n";
            $role_ids[] = $role->id;
        }
        
        $log .= "role_ids: " . JSON_encode($role_ids) . "\n";
        
        foreach(Authorization::find()
            ->active()
            ->grantedTo($this->id)
            ->andWhere(['NOT IN', 'role_id', $role_ids])
            ->andWhere('role_id IS NOT NULL')
            ->all() as $auth)
        {
            $log .= 'Revoked authorization with id ' . $auth->id . "\n";
            $auth->revoke()->save();
        }
        
        //file_put_contents('log_inside_user_fixPermissions_' . $this->username . '.txt', $log);
        
        return true;
    }

    public function getViewLink($options=[])
    {
        return Html::a($this->fullName, ['users/view', 'id'=>$this->id], $options);
    }
    
    public function __toString()
    {
        return $this->getFullName();
    }
        
    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'user_id',
            'prompt' => 'Choose the user',
            'order_by' => ['last_name' => SORT_ASC, 'first_name' => SORT_ASC],
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                ArrayHelper::map(self::find()->active()->orderBy($options['order_by'])->all(), 'id', 'fullName'), 
                ["prompt"=>$options['prompt']]
            );
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->encryptPassword();
        }
        return parent::beforeSave($insert);
    }
    
    public function encryptPassword()
    {
        $this->auth_key = Yii::$app->getSecurity()->generatePasswordHash($this->auth_key);
        return $this;
    }

    public function setRandomValuesForUnusedFields()
    {
        // this is not used anyway
        $this->access_token = rand(100000000, 999999999);
        // this is used for two-factor authentication, but only when the user activates it
        $this->otp_secret = null;
        return $this;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->_doNotLog) {
            \app\components\LogHelper::log($insert ? 'created':'updated', $this, ['excluded'=>[
                'id',
                'auth_key',
                'access_token',
                'otp_secret',
                'created_at',
                'updated_at',
            ]]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function touchLastActionAt($clear=false)
    {
        $this->_doNotLog = true;
        $this->last_action_at = $clear ? null : time();
        $this->save();
    }

    public function getOtherOnlineUsers()
    {
        return User::find()->online()->excludingId($this->id)->select('`id`, `first_name`, `last_name`')->asArray()->all();
    }

    public function getOtherOnlineUsersAsProcessedArray()
    {
        $users = [];
        foreach($this->getOtherOnlineUsers() as $user) {
            $users[] = ['id'=>$user['id'], 'fullName' => $user['first_name'] . ' ' . $user['last_name']];
        }
        return $users;
    }
    
    public function getNumberOfUnseenNotifications()
    {
        return $this->getNotifications()->seen(false)->count();
    }
    
    public function getLastLogins()
    {
        return $this->getActivities()->withActivityType('Login')->orderBy(['happened_at' => SORT_DESC])->limit(5)->all();
    }
    
    public function sendEmailToConfirm2FADisabling()
    {
        $notification = new Notification();
        $user = $this;
        
        $u = sprintf('%d_%d', $user->id, time()+3600);
        $link = Url::to(['site/disable-tfa', 'u'=>$u, 'c'=>md5($u . Yii::$app->params['notificationsKey'])], true);
        
        $notification->user_id = $this->id;
        $notification->subject = Yii::t('app', 'Do you confirm you want two-factor authentication disabled?');
        $notification->plaintext_body = Yii::t('app', 'Hi, {first_name}.\nIf you really want to disable two-factor authentication, click here:\n{link}\n\n(The link expires in one hour.)', ['username'=>$user->first_name, 'link'=>$link]);
        $notification->html_body = \yii\helpers\Markdown::process(Yii::t('app', "Hi, {first_name}.\n\nIf you really want to disable two-factor authentication, click [here]({link}). The link expires in one hour.", ['first_name'=>$user->first_name, 'link'=>$link]));
        $notification->sendEmail(false);
        
        Yii::$app->session->setFlash('success', Yii::t('app', 'Please read the email that has been sent to you ({email}) to confirm the operation.', ['email'=> \app\models\Notification::reduceEmailAddress($user->email)]));
        return true;
    }

    public function sendEmailToInformAbout2FADisabling()
    {
        $notification = new Notification();
        $user = $this;
                
        $notification->user_id = $this->id;
        $notification->subject = Yii::t('app', 'Two-factor authentication');
        $notification->plaintext_body = Yii::t('app', 'Hi, {first_name}.\nTwo-factor authentication has been disabled by an administrator.');
        $notification->html_body = \yii\helpers\Markdown::process(Yii::t('app', "Hi, {first_name}.\n\nTwo-factor authentication has been disabled by an administrator.", ['first_name'=>$user->first_name]));
        $notification->sendEmail(false);
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function getBulkActionMessage($action)
    {
        $messages = [
            'fixPermissions' => "Permissions fixed for {count,plural,=0{no users} =1{one user} other{# users}}.",
        ];
        return ArrayHelper::getValue($messages, $action, '');
    }

}

<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\LogHelper;
use app\models\Affiliation;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property int $rank
 * @property int $status
 * @property string $name
 * @property string|null $description
 * @property string|null $permissions
 * @property string|null $email
 *
 * @property Affiliations[] $affiliations
 * @property Authorizations[] $authorizations
 */
class Role extends \yii\db\ActiveRecord
{

    use ModelTrait;
    
    private $_oldPermissions;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rank', 'status'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['permissions'], 'string', 'max' => 511],
            [['email'], 'string', 'max' => 100],
            [['name', 'description', 'email'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rank' => Yii::t('app', 'Rank'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'permissions' => Yii::t('app', 'Permissions'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Gets query for [[Affiliations]].
     *
     * @return \yii\db\ActiveQuery|AffiliationsQuery
     */
    public function getAffiliations()
    {
        return $this->hasMany(Affiliation::className(), ['role_id' => 'id']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('affiliations', ['role_id' => 'id']);
    }
    
    public function getOrganizationalUnitOfUser($user)
    {
        $a = Affiliation::find()->withUser($user)->withRole($this)->one();
        return $a->organizationalUnit;
    }

    /**
     * Gets query for [[Authorizations]].
     *
     * @return \yii\db\ActiveQuery|AuthorizationsQuery
     */
    public function getAuthorizations()
    {
        return $this->hasMany(Authorizations::className(), ['role_id' => 'id']);
    }

    public function getActiveUsersWithEmailAssociatedToRole()
    {
        $result = [];
        foreach($this->getAffiliations()->joinWith('user')->orderBy(['users.last_name' => SORT_ASC, 'users.first_name'=> SORT_ASC])->all() as $affiliation) {
            $user = $affiliation->getUser()->one();
            if ($affiliation->email){
                $user->email = $affiliation->email;
            }
            $result[] = $user;
        }
        return $result;
    }

    public function getViewLink($options=[])
    {
        return Html::a($this->description, ['roles/view', 'id'=>$this->id], $options);
    }

    public function getStatusView()
    {
        return $this->getTernarianRepresentation($this->status, ['glyphicon-star-empty', 'glyphicon-heart']);
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'role_id',
            'prompt' => 'Choose the role',
            'order_by' => ['rank' => SORT_ASC, 'description' => SORT_ASC,],
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                ArrayHelper::map(self::find()->active()->orderBy($options['order_by'])->all(), 'id', 'description'), 
                ["prompt"=>$options['prompt']]
            );
    }

    public function __construct ($config = [])
    {
        $this->ternarianValues = [
            0 => Yii::t('app', 'Inactive'),
            1 => Yii::t('app', 'Active, no membership required'),
            2 => Yii::t('app', 'Active, membership required'),            
        ];
        return parent::__construct($config);
    }

    public function afterFind()
    {
        $this->_oldPermissions = $this->permissions;
        return parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes)
    {

        $log="started afterSave()\n";

        $log .= JSON_encode($changedAttributes) . "\n";
        
        //$changedPermissions = ArrayHelper::getValue($changedAttributes, 'permissions', null);

        //$log .= 'changed permissions: ' . $changedPermissions . "\n";

        $log .= 'old permissions: ' . $this->_oldPermissions . "\n";

        $log .= 'new permissions: ' . $this->permissions . "\n";
        
        if ($this->_oldPermissions != $this->permissions) {  
            
            // $log.= sprintf("change from «%s» to «%s»\n", $changedPermissions, $this->permissions);
            $log.= sprintf("change from «%s» to «%s»\n", $this->_oldPermissions, $this->permissions);
            
            $newPermissions = $this->_permissionsFromField($this->permissions);
            $oldPermissions = $this->_permissionsFromField($this->_oldPermissions);

            $revokedPermissions = array_diff($oldPermissions, $newPermissions);
            $addedPermissions = array_diff($newPermissions, $oldPermissions);

            $log .= "revoked: " . JSON_encode($revokedPermissions) . "\n";
            $log .= "added: " . JSON_encode($addedPermissions) . "\n";
            
            $users = $this->getUsers()->all();

            $log .= "REVOCATIONS\n";
            foreach($revokedPermissions as $permission) {
                foreach(Authorization::find()
                        ->withActivePermission($permission)
                        ->withRole($this->id)
                        ->all() as $auth) {
                    $auth->revoke()->save();
                    $log .= 'revoked auth: ' . $auth->id . "\n";
                }
            }

            $log .= "ADDITIONS\n";
            foreach($addedPermissions as $permission) {
                if ($permission) {
                    //$log .= "working on $permission...\n";
                    //$items = Authorization::tokenize($permission);
                    //$log .= "   tokenized as " . JSON_encode($items) . "\n";
                    
                    foreach ($users as $user) {
                        $log .= "   *  user: " . $user->username . "\n";
                        $auth = $this->_createAuthorization($permission, $user->id);
                        $log .= "   *  auth_id: " . $auth->id . "\n";
                    }
                }
            }
        }
        else {
            $log .= "no change\n";
        }

        //file_put_contents(sprintf("mylog_%s.txt", date('His')), $log);
        
        LogHelper::log($insert ? 'created':'updated', $this);

        return parent::afterSave($insert, $changedAttributes);

    }

    public function getFormattedUsersEmailAddresses()
    {
        $users=$this->getUsers()->all();
        $lines=[];
        foreach($users as $user) {
            $email = $this->email == 'ou' ? $this->getOrganizationalUnitOfUser($user)->email : $user->email;
            $lines[] = sprintf('%s <%s>,', $user->getFullName(), $email);
        }
        return join("\n", $lines);
    }

    public function addPermissionsForUser(\app\models\User $user)
    {
        $log = 'inside Role::addPermissionsForUser() ' . $user->id . "_role_" . $this->name. "\n";
        
        if ($this->permissions) {
            $log .= sprintf("working on permissions: «%s»\n", $this->permissions);
            foreach($this->_permissionsFromField($this->permissions) as $permission) {
                $log .= "permission: " . $permission . "\n";
                $auth = Authorization::find()
                    ->withActivePermission($permission)
                    ->withRole($this->id)
                    ->grantedTo($user->id)
                    ->one();
                if ($auth) {
                    // $log .= "Found auths " . sizeof($auths) . "\n";
                    $log .= "Found auth: " . $auth->id . ' - ' .$auth->begin_date . " - " . $auth->end_date . "\n";
                }
                else {
                    $auth = $this->_createAuthorization($permission, $user->id);
                    $log .= "Created " . $auth->id . "\n";
                }
            }
        }
        else {
            $log .= "no permissions to work on\n";
        }
        // file_put_contents("log_inside_role_addPermissions_for_" . $user->username . "_role_" . $this->name . ".txt", $log);
    }
    
    public function __toString()
    {
        return $this->description;
    }

    private function _permissionsFromField($field)
    {
        return array_flip(array_flip(array_map('trim', explode(',', $field))));
    }

    private function _createAuthorization($permission, $user_id)
    {
        $items = Authorization::tokenize($permission);
        $auth = new Authorization();
        $auth->importSettings([
            'begin_date' =>date('Y-m-d H:i:s'),
            'end_date' => '2099-12-31 23:59:59',
            'controller_id' => $items['controller_id'],
            'action_id' => $items['action_id'],
            'method' => $items['method'],
            'type'=>'-',
            'user_id' => $user_id,
            'role_id' => $this->id
        ])->save();
        return $auth;
    }


    /**
     * {@inheritdoc}
     * @return RoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoleQuery(get_called_class());
    }
}

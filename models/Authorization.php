<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * This is the model class for table "authorizations".
 *
 * @property int $id
 * @property string $controller_id
 * @property string $action_id
 * @property string $method
 * @property string $type 
 * @property int|null $user_id
 * @property string $begin_date
 * @property string $end_date
 * @property int|null $role_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Activity[] $activities
 * @property User $user
 * @property Role $role
 */
class Authorization extends \yii\db\ActiveRecord
{
    use ModelTrait;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authorizations';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller_id', 'action_id', 'method', 'begin_date', 'end_date'], 'required'],
            [['user_id', 'role_id', 'created_at', 'updated_at'], 'integer'],
            [['begin_date', 'end_date'], 'safe'],
            [['controller_id', 'action_id'], 'string', 'max' => 50],
            [['method'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 1],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'controller_id' => Yii::t('app', 'Controller ID'),
            'action_id' => Yii::t('app', 'Action ID'),
            'method' => Yii::t('app', 'Method'),
            'type' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'begin_date' => Yii::t('app', 'Begin Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'role_id' => Yii::t('app', 'Role ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Activities]].
     *
     * @return \yii\db\ActiveQuery|ActivityQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['authorization_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery|RoleQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
    
    public function getIdentifier()
    {
        return sprintf('%s/%s/%s', $this->controller_id, $this->action_id, $this->method);
    }
    
    public function revoke()
    {
        $this->end_date = date('Y-m-d H:i:s', time()-1);
        return $this;
    }

    public static function getAuthorizedControllers()
    {
        $controllers = [
            'users' => [
                'icon' => 'users',
                'color' => '#FFA500',
                'title' => 'Users',
                'description' => 'People you work with',
            ],
            'organizational-units' => [
                'icon' => 'puzzle-piece',
                'color' => '#ffe500',
                'title' => 'Organizational Units',
                'description' => 'Teams and clubs',
            ],
            'roles' => [
                'icon' => 'briefcase',
                'color' => '#A52A2A',
                'title' => 'Roles',
                'description' => 'Hats people can wear',
            ],
            'affiliations' => [
                'icon' => 'random',
                'color' => '#004487',
                'title' => 'Affiliations',
                'description' => 'Who works where',
            ],
            'authorizations' => [
                'icon' => 'key',
                'color' => '#008000',
                'title' => 'Authorizations',
                'description' => 'Who can do what',
            ],
            'activities' => [
                'icon' => 'list',
                'color' => '#BFBFBF',
                'title' => 'Activities',
                'description' => 'A log of what happened',
            ],
            'backend' => [
                'icon' => 'cog',
                'color' => '#7F7F7F',
                'title' => 'Back End',
                'description' => 'Basic configurations',
            ],
            'project-submissions' => [
                'icon' => 'list-alt',
                'color' => '#002E00',
                'title' => 'Projects',
                'description' => 'Things your team plan and do',
            ],
            'projects-management' => [
                'icon' => 'newspaper-o',
                'color' => '#6B8AA8',
                'title' => 'Projects Management',
                'description' => 'Things planned by teams',
            ],
            'reimbursements' => [
                'icon' => 'credit-card',
                'color' => '#093609',
                'title' => 'Reimbursements',
                'description' => 'Ask for money',
            ],
            'reimbursements-management' => [
                'icon' => 'credit-card',  //?
                'color' => '#093609',
                'title' => 'Reimbursements Management',
                'description' => 'Teams\'s Requests',
            ],
            'periodical-report-submissions' => [
                'icon' => 'money', //?
                'color' => '#093609',
                'title' => 'Accounting',
                'description' => 'Use of money',
            ],
            'periodical-reports-management' => [
                'icon' => 'book',
                'color' => '#093609',
                'title' => 'Financial Reports Management',
                'description' => 'What has been recorded',
            ],
            'office-transactions' => [
                'icon' => 'table',
                'color' => '#A52A2A',
                'title' => 'Office Transactions',
                'description' => 'Debits and credits centrally recorded',
            ],
            'fast-transactions' => [
                'icon' => 'paper-plane',
                'color' => '#0000FF',
                'title' => 'Fast Transactions',
                'description' => 'Transactions on the express track',
            ],
            'events' => [
                'icon' => 'calendar-o',
                'color' => '#093609',
                'title' => 'Events',
                'description' => 'Planned activities',
            ],
            'events-management' => [
                'icon' => 'calendar',
                'color' => '#093609',
                'title' => 'Events Management',
                'description' => 'What\'s gonna happen',
            ],
            'shorteners' => [
                'icon' => 'link',
                'color' => '#008000',
                'title' => 'Shorteners',
                'description' => 'Customized short URLs',
            ],
            'social-media' => [
                'icon' => 'rocket',
                'color' => '#F908DF',
                'title' => 'Social Media',
                'description' => 'Schedule the posts',
            ],
            'my-organizational-unit' => [
                'icon' => 'picture-o',
                'color' => '#FFA500',
                'title' => 'My Organizational Unit',
                'description' => 'People I work with',
            ],
            
            /*
            'notifications' => [
                'icon' => 'envelope',
                'color' => '#093609',
                'title' => 'Notifications',
                'description' => 'Messages for you',
            ],
            */
        ];
        
        $authorizations = array_map(
            function($x) {
                return $x->controller_id;
                },
            Authorization::find()
                ->active()
                ->withAction('index')
                ->requestedBy(Yii::$app->user)
                ->select(['controller_id'])
                ->all()
            );
            
        $authorizedControllers = [];
        foreach($controllers as $key=>$value)
        {
            if (in_array($key, $authorizations)) {
                $authorizedControllers[$key] = $value;
            }
        }
        
        return $authorizedControllers;
        
    }
    
    public function getViewLink($options=[])
    {
        return Html::a($this->identifier, ['authorizations/view', 'id'=>$this->id], $options);
    }
    
    public function canBeDeleted()
    {
        return $this->getActivities()->count() == 0;
    }

    public function beforeDelete()
    {
        \app\components\LogHelper::log('deleted', $this);
        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        \app\components\LogHelper::log($insert ? 'created':'updated', $this);
        return parent::afterSave($insert, $changedAttributes);
    }
        
    public static function tokenize($permission) 
    {
        $tokens = array_map('trim', explode('/', $permission));
        $numberOfItems = sizeof($tokens);
        if ($numberOfItems==0) {
            return null;
        }
        $items = [
            'controller_id' => str_replace("\\", "/", $tokens[0]),
            'action_id' => '*',
            'method' => '*',
        ];
        if ($numberOfItems>1) {
            $items['action_id'] = $tokens[1];
        }
        if ($numberOfItems>2) {
            $items['method'] = $tokens[2];
        }
        return $items;
    }

    /**
     * {@inheritdoc}
     * @return AuthorizationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthorizationQuery(get_called_class());
    }
}

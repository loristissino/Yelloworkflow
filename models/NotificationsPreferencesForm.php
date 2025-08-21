<?php

namespace app\models;

use Yii;
use yii\base\Model;

class NotificationsPreferencesForm extends Model
{
    public $available_notifications;
    public $accepted_notifications;
    public $notifications;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['notifications'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'notifications' => Yii::t('app', 'Accepted notifications'),
        ];
    }
   
    public function loadValuesFromUserSettings()
    {
        $notifications = \app\models\NotificationTemplate::find()->orderBy(['title'=>'ASC'])->all();
        
        $this->available_notifications = [];
        
        foreach($notifications as $notification) {
            $this->available_notifications[$notification->id] = $notification->title;
        }
        
        $this->notifications = Yii::$app->user->identity->getPreference('notifications', false);
        
        if ($this->notifications === false){
            $this->notifications = array_keys($this->available_notifications);
        }
        
    }
        
    public function save()
    {
        Yii::$app->user->identity->setPreference('notifications', $this->notifications)->save(false);
        return true;
    }
    
}

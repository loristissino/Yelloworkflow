<?php

namespace app\components;
use yii\helpers\ArrayHelper;
use app\models\Activity;
use app\models\Authorization;
use app\models\Notification;
use app\models\NotificationTemplate;
use yii\helpers\Url;
use yii\helpers\Markdown;

class LogHelper {
    public static function log($activity_type, $model, $options=[])
    {
        // $options['excluded'] could be an array of properties to not store
        $options['excluded'] = ArrayHelper::getValue($options, 'excluded', [
            // we normally exclude this fields because they are stored anyway
            'id', 
            'created_at',
            'updated_at',
        ]);
        
        $activity = new Activity();
        
        if (\Yii::$app instanceof \yii\console\Application) {
            // Running in console
            $userId = -1; // system user that must exist!
            $authorizationId = null;
        } 
        else {
            // Running in web application
            $userId = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $authorizationId = \Yii::$app->controller->getAuthorizationId();
            if (!$authorizationId){
                $authorizationId = null; // for PWA
            }
        }
        
        $activity->user_id = $userId;
        $activity->activity_type = $activity_type;
        $activity->model = $model::className();
        $activity->model_id = isset($model->id) ? $model->id: 0;

        $values = array_diff_key(
            ArrayHelper::toArray($model, [], false),
            array_flip($options['excluded'])
            );
            
        $related_items = ArrayHelper::getValue($options, 'related', []);
        foreach($related_items as $related_item) {
            $values['related'][$related_item] = array_diff_key(
                ArrayHelper::toArray($model->$related_item),
                array_flip($options['excluded'])
                );
        }
        
        if (($cd = ArrayHelper::getValue($options, 'change_description', '')) !=='') {
            $values['change_description'] = $cd;
        }
        
        $activity->info = JSON_encode($values);
        
        $activity->authorization_id = $authorizationId;
        $activity->save(false);
        
        if (!ArrayHelper::getValue($options, 'without-notifications', false)) {
            self::notify($model);
        }
    }
    
    public static function notify($model, NotificationTemplate $template=null) {
        // is there a better way to find out if the model has the workflow behavior?
        
        $count = 0;
        
        if (!$model->hasProperty('wf_status'))
            return $count;
                        
        $notifications = $model->getWorkflowStatus()->getMetadata('notifications');
                        
        if (!$notifications) {
            return $count;
        }
        
        if (!$template) { // for reminders we receive the template as a parameter
            $template = NotificationTemplate::find()->withCode($model->getWorkflowStatus()->getId())->one();
        }
        if (!$template) {
            return $count;
        }
        
        $notification_fields = $model->getWorkflowStatus()->getMetadata('notification_fields', []);
        
        $subject = $template->subject;
        $plaintextBody = $template->plaintext_body;
        $markdownBody = $template->md_body;

        foreach($notification_fields as $field) {
            if (isset($model->$field)) {
                $subject = str_replace('{' . $field . '}', $model->$field, $subject);
                $plaintextBody = str_replace('{' . $field . '}', $model->$field, $plaintextBody);
                $markdownBody = str_replace('{' . $field . '}', $model->$field, $markdownBody);
            }
        }
        
        foreach($notifications as $permission=>$type){
            $authorizations = Authorization::find()->withActivePermission($permission)->all();
                                                
            if ($type == 'ou') {
                $ou = $model->organizationalUnit;
                $authorizations = array_filter($authorizations, function($auth) use ($ou) {
                    return $ou->getUsers()->withId($auth->user_id)->one() !== null;
                });
            }

            $url = Url::toRoute([$permission, 'id' => $model->id], true);
            
            foreach ($authorizations as $authorization) {
                
                $notification = new Notification();
                
                $notification->email = $authorization->user->email; // the default is to send the email to the user
                
                if ($authorization->role && $authorization->role->email) {
                    // but if the role has an associated email we use that one
                    if ($authorization->role->email == 'ou') {
                        // unless the role has 'ou' as email, which means that we use the email of the organizational unit
                        $notification->email = $ou->email;
                    }
                    else {
                        $notification->email = $authorization->role->email;
                    }
                }
                
                if (!$notification->email) {
                    continue;
                }
                
                $notification->user_id = $authorization->user_id;
                $notification->subject = $subject;
                $notification->plaintext_body = str_replace('{url}', $url, $plaintextBody);
                $notification->html_body = Markdown::process(str_replace('{url}', $url, $markdownBody));
                
                $accepted_notifications = $authorization->user->getPreference('notifications', false);
                                
                if (is_array($accepted_notifications) and !in_array($template->id, $accepted_notifications)) {
                    $notification->sent_at = -1;
                }
                
                $notification->save();
                $count++;
                // $notification->sendEmail(); // we'll send the email later, see notifications/send 
            }
        }
        return $count;
        
    }
}

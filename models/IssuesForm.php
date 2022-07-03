<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Authorization;
use yii\helpers\Markdown;

/**
 * IssuesForm is the model behind the issues form.
 */
class IssuesForm extends Model
{
    public $subject;
    public $description;
    public $reference;
    public $url;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['subject', 'description'], 'required'],
            [['reference', 'url'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('app', 'Subject'),
            'description' => Yii::t('app', 'Description'),
            'reference' => Yii::t('app', 'Reference'),
            'url' => Yii::t('app', 'URL'),
        ];
    }

    public function notify()
    {
        if ($this->validate()) {
            $notification = new Notification();
            $notification->subject = Yii::t('app', '[New issue] {subject}', ['subject'=>$this->subject]);
            
            $notification->plaintext_body = sprintf("%s\n\n------\n\n[%s](%s)\n\n%s", 
                $this->description,
                $this->reference,
                $this->url,
                Yii::$app->user->identity->getFullName()
            );
            $notification->html_body = Markdown::process($notification->plaintext_body);
            
            // we send a copy to the logged-in user who sent the request
            $notification->user_id = Yii::$app->user->identity->id;
            $notification->save();

            // ... and then we send a notification to all the users with the 'issues' authorization
            foreach(Authorization::find()->withController('issues')->all() as $item) {
                $n = $notification->cloneModel();
                $notification->user_id = $item->user_id;
                $notification->save();
            }
            
            return true;
        }
        return false;
    }
}

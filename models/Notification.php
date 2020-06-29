<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string|null $plaintext_body
 * @property string|null $html_body
 * @property int $created_at
 * @property int|null $seen_at
 * @property int|null $sent_at
 * @property string|null $email
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => 'created_at',
                //'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'subject', ], 'required'],
            [['user_id', 'created_at', 'seen_at', 'sent_at'], 'integer'],
            [['plaintext_body', 'html_body'], 'string'],
            [['subject'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'subject' => Yii::t('app', 'Subject'),
            'plaintext_body' => Yii::t('app', 'Plaintext Body'),
            'html_body' => Yii::t('app', 'Html Body'),
            'created_at' => Yii::t('app', 'Created At'),
            'seen_at' => Yii::t('app', 'Seen At'),
            'sent_at' => Yii::t('app', 'Sent At'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    public function markSeen()
    {
        $this->touch('seen_at');
        return $this;
    }

    public function markUnseen()
    {
        $this->seen_at = null;
        return $this;
    }
    
    public static function getBulkActionMessage($action)
    {
        $messages = [
            'markSeen' => "{count,plural,=0{no notification has} =1{One notification has} other{# notifications have}} been marked seen.",
            'markUnseen' => "{count,plural,=0{no notification has} =1{One notification has} other{# notifications have}} been marked unseen.",
            'delete' => "{count,plural,=0{no notification has} =1{One notification has} other{# notifications have}} been deleted.",
        ];
        return ArrayHelper::getValue($messages, $action, '');
    }

    /**
     * {@inheritdoc}
     * @return NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationQuery(get_called_class());
    }
}

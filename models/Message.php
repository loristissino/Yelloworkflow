<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;


use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $subject
 * @property string|null $plaintext_body
 * @property string|null $html_body
 * @property string|null $headers
 * @property int $created_at
 * @property int|null $sent_at
 * @property string $email
 * @property string $addressee
 * @property string $apikey
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
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
            [['subject', 'created_at', 'email'], 'required'],
            [['plaintext_body', 'html_body', 'headers'], 'string'],
            [['created_at', 'sent_at'], 'integer'],
            [['subject'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['addressee'], 'string', 'max' => 255],
            [['apikey'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'plaintext_body' => Yii::t('app', 'Plaintext Body'),
            'html_body' => Yii::t('app', 'Html Body'),
            'headers' => Yii::t('app', 'Headers'),
            'created_at' => Yii::t('app', 'Created At'),
            'sent_at' => Yii::t('app', 'Sent At'),
            'email' => Yii::t('app', 'Email'),
            'addressee' => Yii::t('app', 'Addressee'),
            'apikey' => Yii::t('app', 'Apikey')
        ];
    }

    public function sendEmail($save=true) {
        if (Yii::$app->mailer->compose()
            ->setApikey($this->apikey)
            ->setTo($this->email, $this->addressee)
            ->setSubject($this->subject)
            ->setHtmlBody($this->html_body)
            ->send()
        ) {
            if ($save) {
                $this->sent_at = time();
                $this->apikey = null;
                $this->save();
            }
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }
}

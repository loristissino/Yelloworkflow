<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification_templates".
 *
 * @property int $id
 * @property string $code
 * @property string $title
 * @property string $subject
 * @property string $plaintext_body
 * @property string $html_body
 * @property string $md_body
 */
class NotificationTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title', 'subject', 'plaintext_body'], 'required'],
            [['plaintext_body', 'html_body', 'md_body'], 'string'],
            [['code'], 'string', 'max' => 40],
            [['title'], 'string', 'max' => 100],
            [['subject'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'title' => Yii::t('app', 'Title'),
            'subject' => Yii::t('app', 'Subject'),
            'plaintext_body' => Yii::t('app', 'Plaintext Body'),
            'html_body' => Yii::t('app', 'Html Body'),
            'md_body' => Yii::t('app', 'Markdown Body'),
        ];
    }

    public function cloneModel()
    {
        $model = new NotificationTemplate();
        $model->attributes = $this->attributes;
        $model->id = null;
        $model->code .= '_' . rand(1000,9999);
        $model->save();
        return $model;
    }

    /**
     * {@inheritdoc}
     * @return NotificationTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationTemplateQuery(get_called_class());
    }
}

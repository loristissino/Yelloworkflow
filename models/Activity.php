<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "activities".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $activity_type
 * @property string|null $model
 * @property int|null $model_id
 * @property string $info
 * @property int|null $authorization_id
 * @property int $happened_at
 *
 * @property Authorization $authorization
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activities';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'happened_at',
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
            [['user_id', 'model_id', 'authorization_id', 'happened_at'], 'integer'],
            [['activity_type', 'info'], 'required'],
            [['info'], 'string'],
            [['activity_type'], 'string', 'max' => 100],
            [['model'], 'string', 'max' => 40],
            [['authorization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authorization::className(), 'targetAttribute' => ['authorization_id' => 'id']],
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
            'activity_type' => Yii::t('app', 'Activity Type'),
            'model' => Yii::t('app', 'Object'),
            'model_id' => Yii::t('app', 'ID'),
            'info' => Yii::t('app', 'Info'),
            'authorization_id' => Yii::t('app', 'Authorization'),
            'happened_at' => Yii::t('app', 'Happened At'),
        ];
    }

    /**
     * Gets query for [[Authorization]].
     *
     * @return \yii\db\ActiveQuery|AuthorizationQuery
     */
    public function getAuthorization()
    {
        return $this->hasOne(Authorization::className(), ['id' => 'authorization_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getViewLink($options=[])
    {
        return Html::a($this->fullName, ['activities/view', 'id'=>$this->id], $options);
    }

    /**
     * {@inheritdoc}
     * @return ActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityQuery(get_called_class());
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_agents".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $hash
 * @property string $info
 * @property int $updated_at
 *
 * @property User $user
 */
class UserAgent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_agents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'updated_at'], 'integer'],
            [['hash', 'info', 'updated_at'], 'required'],
            [['info'], 'string'],
            [['hash'], 'string', 'max' => 40],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'hash' => Yii::t('app', 'Hash'),
            'info' => Yii::t('app', 'Info'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
     * {@inheritdoc}
     * @return UserAgentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAgentQuery(get_called_class());
    }
}

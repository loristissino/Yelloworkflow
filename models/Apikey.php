<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "apikeys".
 *
 * @property int $id
 * @property int $user_id
 * @property string $app
 * @property string $value
 *
 * @property User $user
 */
class Apikey extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apikeys';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'app', 'value'], 'required'],
            [['user_id'], 'integer'],
            [['app'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 32],
            [['value'], 'unique'],
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
            'app' => Yii::t('app', 'App'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    public function getView()
    {
        return sprintf('<tt>%s</tt> (%s) [%s]', 
            $this->value, 
            
            $this->app,
            
            \Yii\helpers\Html::a("Delete", ['site/apikey', 'action'=>'delete', 'id'=>$this->id], ['data-method'=>'post']));
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

    public static function getUserByAccessToken($token)
    {
        $row = self::find()
        ->where(['value' => $token])
        ->one();
        return $row ? User::findOne(['id' => $row->user_id]) : null;
    }

    public static function generateRandomValue()
    {
        $value = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for ($i=0; $i<32; $i++) {
            $value .= substr($chars, rand(0,strlen($chars)), 1);
        }
        return $value;
    }

    /**
     * {@inheritdoc}
     * @return ApikeyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApikeyQuery(get_called_class());
    }
}

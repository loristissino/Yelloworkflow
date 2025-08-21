<?php

namespace app\models;

use Yii;

/**
 * This is the model class for view "viewed_ou_main_activities".
 *
 * @property int $id
 * @property int $happened_at
 * @property string $activity_type
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property int $organizational_unit_id
 * @property string $name
 * @property int $role_id
 * @property string|null $role_description
 */
class ViewedOuMainActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'viewed_ou_main_activities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'happened_at', 'user_id', 'organizational_unit_id', 'role_id'], 'integer'],
            [['activity_type', 'name'], 'string', 'max' => 100],
            [['first_name', 'last_name'], 'string', 'max' => 40],
            [['role_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'happened_at' => Yii::t('app', 'Happened At'),
            'activity_type' => Yii::t('app', 'Activity Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit ID'),
            'name' => Yii::t('app', 'Organizational Unit'),
            'role_id' => Yii::t('app', 'Role ID'),
            'role_description' => Yii::t('app', 'Role Description'),
        ];
    }
    
    public static function primaryKey()
    {
        // this is a view, so this is not really a primary key
        return ['id'];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "affiliations".
 *
 * @property int $id
 * @property int $user_id
 * @property int $organizational_unit_id
 * @property int $role_id
 * @property int $rank
 *
 * @property User $user
 * @property OrganizationalUnit $organizationalUnit
 * @property Role $role
 */
class Affiliation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'affiliations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'organizational_unit_id', 'role_id'], 'required'],
            [['user_id', 'organizational_unit_id', 'role_id', 'rank'], 'integer'],
            [['user_id', 'organizational_unit_id'], 'unique', 'targetAttribute' => ['user_id', 'organizational_unit_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'role_id' => Yii::t('app', 'Role'),
            'rank' => Yii::t('app', 'Rank'),
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
     * Gets query for [[OrganizationalUnit]].
     *
     * @return \yii\db\ActiveQuery|OrganizationalUnitQuery
     */
    public function getOrganizationalUnit()
    {
        return $this->hasOne(OrganizationalUnit::className(), ['id' => 'organizational_unit_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery|RoleQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function beforeDelete()
    {
        \app\components\LogHelper::log('deleted', $this);
        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        if (!$this->rank)
            $this->rank = 1;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        \app\components\LogHelper::log($insert ? 'created':'updated', $this);
        return parent::afterSave($insert, $changedAttributes);
    }

    public function getViewLink($options=[])
    {
        return Yii\helpers\Html::a($this->id, ['affiliations/view', 'id'=>$this->id], $options);
    }

    /**
     * {@inheritdoc}
     * @return AffiliationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AffiliationQuery(get_called_class());
    }
}

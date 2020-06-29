<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "expense_types".
 *
 * @property int $id
 * @property int $rank
 * @property string $name
 * @property int $status
 * @property int|null $organizational_unit_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrganizationalUnit $organizationalUnit
 * @property PlannedExpense[] $plannedExpenses
 */
class ExpenseType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense_types';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rank', 'name'], 'required'],
            [['rank', 'status', 'organizational_unit_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationalUnit::className(), 'targetAttribute' => ['organizational_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rank' => Yii::t('app', 'Rank'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Is Active'),
            'organizational_unit_id' => Yii::t('app', 'Organizational Unit'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
     * Gets query for [[PlannedExpenses]].
     *
     * @return \yii\db\ActiveQuery|PlannedExpenseQuery
     */
    public function getPlannedExpenses()
    {
        return $this->hasMany(PlannedExpense::className(), ['expense_type_id' => 'id']);
    }

    public function getViewLink($options=[])
    {
        return Yii\helpers\Html::a($this->name, ['expense-types/view', 'id'=>$this->id], $options);
    }

    public static function getDropdown($form, $model, $options=[])
    {
        $options = array_merge([
            'field_name' => 'expense_type_id',
            'prompt' => Yii::t('app', 'Choose the expense type'),
            'order_by' => ['rank' => SORT_ASC, 'name' => SORT_ASC],
        ], $options);
        return $form
            ->field($model, $options['field_name'])
            ->dropDownList(
                ArrayHelper::map(self::find()->active()->orderBy($options['order_by'])->all(), 'id', 'name'), 
                ["prompt"=>$options['prompt']]
            );
    }

    /**
     * {@inheritdoc}
     * @return ExpenseTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExpenseTypeQuery(get_called_class());
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "planned_expenses".
 *
 * @property int $id
 * @property int $project_id
 * @property int $expense_type_id
 * @property string $description
 * @property float $amount
 * @property string $notes
 *
 * @property Project $project
 * @property ExpenseType $expenseType
 */
class PlannedExpense extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planned_expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'expense_type_id', 'description', 'amount'], 'required'],
            [['project_id', 'expense_type_id'], 'integer'],
            [['amount'], 'number', 'min' => 0, 'max'=>1000000],
            [['notes'], 'string'],
            [['description'], 'string', 'max' => 255],
            [['notes', 'description'], 'filter', 'filter'=>function($value) {return trim(strip_tags($value));}],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['expense_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseType::className(), 'targetAttribute' => ['expense_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'expense_type_id' => Yii::t('app', 'Expense Type'),
            'description' => Yii::t('app', 'Description'),
            'amount' => Yii::t('app', 'Amount'),
            'notes' => Yii::t('app', 'Notes'),
        ];
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery|ProjectQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * Gets query for [[ExpenseType]].
     *
     * @return \yii\db\ActiveQuery|ExpenseTypeQuery
     */
    public function getExpenseType()
    {
        return $this->hasOne(ExpenseType::className(), ['id' => 'expense_type_id']);
    }

    public function setProject(Project $project)
    {
        $this->project_id = $project->id;
        return $this;
    }
    
    public function getFormattedAmount()
    {
        return  Yii::$app->formatter->asCurrency($this->amount);
    }    
    
    public function beforeSave($insert)
    {
        // only planned expenses of projects with draft status can be updated
        if (!$this->getProject()->one()->isDraft) {
            $this->addError('*', 'It is not possible to edit planned expenses of projects that do not have draft status.');
            return false;
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @return PlannedExpenseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlannedExpenseQuery(get_called_class());
    }
}

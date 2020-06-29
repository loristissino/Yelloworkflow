<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PlannedExpense]].
 *
 * @see PlannedExpense
 */
class PlannedExpenseQuery extends \yii\db\ActiveQuery
{
    public function ofProject(Project $project)
    {
        return $this->andWhere(['=', 'project_id', $project->id]);
    }
    
    /**
     * {@inheritdoc}
     * @return PlannedExpense[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PlannedExpense|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

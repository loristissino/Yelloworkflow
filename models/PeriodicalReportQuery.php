<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PeriodicalReport]].
 *
 * @see PeriodicalReport
 */
class PeriodicalReportQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        return $this->andWhere([$active ? '<>': '=', 'wf_status', 'ProjectWorkflow/archived']);
    }
    
    public function draft($draft=true)
    {
        return $this->andWhere([$draft ? '=' : '<>', 'wf_status', 'PeriodicalReportWorkflow/draft']);
    }


    public function open()
    {
        return $this
            ->andWhere(['<>', 'wf_status', 'PeriodicalReportWorkflow/closed'])
            ->andWhere(['<>', 'wf_status', 'PeriodicalReportWorkflow/archived'])
            ;
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withOrganizationalUnitId($id)
    {
        return $this->andWhere(['=', 'organizational_unit_id', $id]);
    }
    
    public function withWithinDate($date)
    {
        return $this
            ->andWhere(['<=', 'begin_date', $date])
            ->andWhere(['>=', 'end_date', $date])
            ;
    }
    
    public function toRemindToday()
    {
        return $this
            ->andWhere("wf_status IN ('PeriodicalReportWorkflow/draft', 'PeriodicalReportWorkflow/questioned')")
            ->andWhere('due_date IS NOT NULL')
            ->andWhere(['<=', 'due_date', date('Y-m-d')])
            ->andWhere(['=', 'MOD(DATEDIFF(CURDATE(), `due_date`), 2)', 0]) // we send the reminder every two days...
            ;
    }

    /**
     * {@inheritdoc}
     * @return PeriodicalReport[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PeriodicalReport|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

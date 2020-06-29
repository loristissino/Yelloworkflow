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
        return $this;
        /* TODO
        if ($active) {
        return $this
            ->andWhere(['<>', 'wf_status', 'ProjectWorkflow/archived'])
            ->andWhere(['<>', 'wf_status', 'ProjectWorkflow/deleted'])
            ;
        }
        else {
            return $this->andWhere(new OrCondition([
                ['=', 'wf_status', 'ProjectWorkflow/archived'],
                ['=', 'wf_status', 'ProjectWorkflow/deleted']
            ]));
        }
        */
    }
    
    public function draft($draft=true)
    {
        return $this->andWhere([$draft ? '=' : '<>', 'wf_status', 'PeriodicalReportWorkflow/draft']);
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

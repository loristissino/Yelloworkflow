<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PeriodicalReportComment]].
 *
 * @see PeriodicalReportComment
 */
class PeriodicalReportCommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    
    public function after($instant)
    {
        return $this->andWhere(['>', 'updated_at', $instant]);
    }

    public function forPeriodicalReport(PeriodicalReport $periodicalReport)
    {
        return $this->andWhere(['=', 'periodical_report_id', $periodicalReport->id]);
    }

    public function ofUser($user_id)
    {
        return $this->andWhere(['=', 'user_id', $user_id]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }


    /**
     * {@inheritdoc}
     * @return PeriodicalReportComment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PeriodicalReportComment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;
use yii\db\conditions\OrCondition;

/**
 * This is the ActiveQuery class for [[Transaction]].
 *
 * @see Transaction
 */
class TransactionQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        if ($active) {
        return $this
            ->andWhere(['<>', 'transactions.wf_status', 'TransactionWorkflow/archived'])
            ->andWhere(['<>', 'transactions.wf_status', 'TransactionWorkflow/deleted'])
            ;
        }
        else {
            return $this->andWhere(new OrCondition([
                ['=', 'transactions.wf_status', 'TransactionWorkflow/archived'],
                ['=', 'transactions.wf_status', 'TransactionWorkflow/deleted']
            ]));
        }
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function ofPeriodicalReport(PeriodicalReport $pr)
    {
        return $this->andWhere(['=', 'periodical_report_id', $pr->id]);
    }

    public function withStatus($status)
    {
        return $this->andWhere(['=', 'transactions.wf_status', $status]);
    }

    public function withOneOfStatuses($statuses=[])
    {
        $array = [];
        foreach ($statuses as $status) {
            $array[] = ['=', 'transactions.wf_status', $status];
        }
        return $this->andWhere(new OrCondition($array));
    }

    /**
     * {@inheritdoc}
     * @return Transaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Transaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

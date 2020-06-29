<?php

namespace app\models;

use app\models\Posting;
use app\models\Transaction;

/**
 * This is the ActiveQuery class for [[Posting]].
 *
 * @see Posting
 */
class PostingQuery extends \yii\db\ActiveQuery
{
    public function ofTransaction(Transaction $transaction)
    {
        return $this->andWhere(['=', 'transaction_id', $transaction->id]);
    }

    public function draft($draft=true)
    {
        return $this->andWhere([$draft ? '=' : '<>', 'transactions.wf_status', 'TransactionWorkflow/draft']);
    }
    
    public function withAccountId($id)
    {
        return $this->andWhere(['=', 'accounts.id', $id]);
    }

    public function withAccountCode($code)
    {
        return $this->andWhere(['=', 'accounts.code', $code]);
    }
    
    public function withOrganizationalUnitId($id)
    {
        return $this->andWhere(['=', 'periodical_reports.organizational_unit_id', $id]);
    }

    public function relatedToProject(Project $project)
    {
        return $this->andWhere(['=', 'transactions.project_id', $project->id]);
    }

    public function withRealAccount($real=true)
    {
        return $this->andWhere([$real?'=':'<>', 'accounts.represents', 'R']);
    }

    public function withTransactionStatus($status)
    {
        return $this->andWhere(['=', 'transactions.wf_status', $status]);
    }

    /**
     * {@inheritdoc}
     * @return Posting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Posting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Reimbursement]].
 *
 * @see Reimbursement
 */
class ReimbursementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Reimbursement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Reimbursement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

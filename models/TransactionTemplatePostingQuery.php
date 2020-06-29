<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TransactionTemplatePosting]].
 *
 * @see TransactionTemplatePosting
 */
class TransactionTemplatePostingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function ofTransactionTemplate(TransactionTemplate $template)
    {
        return $this->andWhere(['=', 'transaction_template_id', $template->id]);
    }

    /**
     * {@inheritdoc}
     * @return TransactionTemplatePosting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TransactionTemplatePosting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

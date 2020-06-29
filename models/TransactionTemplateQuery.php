<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TransactionTemplate]].
 *
 * @see TransactionTemplate
 */
class TransactionTemplateQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }
    
    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function officeOnly($office_only=true)
    {
        return $office_only ? $this->andWhere(['<>', 'o_title', '']) : $this;
    }

    /**
     * {@inheritdoc}
     * @return TransactionTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TransactionTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

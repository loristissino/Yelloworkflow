<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ExpenseType]].
 *
 * @see ExpenseType
 */
class ExpenseTypeQuery extends \yii\db\ActiveQuery
{

    public function active($active=true)
    {
        return $this->andWhere(['=', 'status', $active ? 1 : 0]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    /**
     * {@inheritdoc}
     * @return ExpenseType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ExpenseType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Role]].
 *
 * @see Role
 */
class RoleQuery extends \yii\db\ActiveQuery
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
     * @return Role[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Role|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

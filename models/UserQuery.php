<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        return $this->andWhere(['=', 'status', $active ? 1 : 0]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withUsername($username)
    {
        return $this->andWhere(['=', 'username', $username]);
    }

    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

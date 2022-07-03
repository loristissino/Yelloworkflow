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

    public function excludingId($id)
    {
        return $this->andWhere(['<>', 'id', $id]);
    }

    public function withUsername($username)
    {
        return $this->andWhere(['=', 'username', $username]);
    }

    public function withEmail($email)
    {
        return $this->andWhere(['=', 'email', $email]);
    }
        
    public function online()
    {
        return $this->andWhere(['>', 'last_action_at', time()-600]);
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

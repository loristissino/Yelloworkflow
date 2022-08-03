<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserAgent]].
 *
 * @see UserAgent
 */
class UserAgentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    
    public function withHash($hash)
    {
        return $this->andWhere(['=', 'hash', $hash]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withUserId($id)
    {
        return $this->andWhere(['=', 'user_id', $id]);
    }

    /**
     * {@inheritdoc}
     * @return UserAgent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserAgent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

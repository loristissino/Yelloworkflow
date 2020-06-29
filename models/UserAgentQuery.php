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

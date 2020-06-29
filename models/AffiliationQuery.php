<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Affiliation]].
 *
 * @see Affiliation
 */
class AffiliationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function withUser(User $user)
    {
        return $this->andWhere(['=', 'user_id', $user->id]);
    }

    /**
     * {@inheritdoc}
     * @return Affiliation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Affiliation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

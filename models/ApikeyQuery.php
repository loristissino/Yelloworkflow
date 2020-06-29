<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Apikey]].
 *
 * @see Apikey
 */
class ApikeyQuery extends \yii\db\ActiveQuery
{

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }
    
    public function ofUser(\app\components\CUser $user)
    {
        return $this->andWhere(['=', 'user_id', $user->id]);
    }

    /**
     * {@inheritdoc}
     * @return Apikey[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Apikey|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

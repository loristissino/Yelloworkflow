<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Notification]].
 *
 * @see Notification
 */
class NotificationQuery extends \yii\db\ActiveQuery
{
    public function seen($seen=true)
    {
        if ($seen)
            return $this->andWhere('seen_at IS NOT NULL');
        else
            return $this->andWhere('seen_at IS NULL');
    }

    public function forUser($user_id)
    {
        return $this->andWhere(['=', 'user_id', $user_id]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    /**
     * {@inheritdoc}
     * @return Notification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Notification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

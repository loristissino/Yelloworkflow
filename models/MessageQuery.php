<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function sent($sent=true)
    {
        if ($sent)
            return $this->andWhere('sent_at IS NOT NULL');
        else
            return $this->andWhere('sent_at IS NULL');
    }

    /**
     * {@inheritdoc}
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

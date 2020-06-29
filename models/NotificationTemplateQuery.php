<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[NotificationTemplate]].
 *
 * @see NotificationTemplate
 */
class NotificationTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function withCode($code)
    {
        return $this->andWhere(['=', 'code', $code]);
    }

    /**
     * {@inheritdoc}
     * @return NotificationTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return NotificationTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

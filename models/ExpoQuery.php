<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Expo]].
 *
 * @see Expo
 */
class ExpoQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        return $this
            ->andWhere([$active ? '=': '<>', 'wf_status', 'ExpoWorkflow/active'])
        ;
    }

    public function ongoing()
    {
        return $this
            ->andWhere(['<=', 'begin_date', date('Y-m-d')])
            ->andWhere(['>=', 'end_date', date('Y-m-d')])
        ;
    }

    /**
     * {@inheritdoc}
     * @return Expo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Expo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

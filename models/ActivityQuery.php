<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Activity]].
 *
 * @see Activity
 */
class ActivityQuery extends \yii\db\ActiveQuery
{
    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withModel($model)
    {
        return $this->andWhere(['=', 'model', $model]);
    }

    public function withModelId($model_id)
    {
        return $this->andWhere(['=', 'model_id', $model_id]);
    }

    public function withActivityType($type)
    {
        return $this->andWhere(['=', 'activity_type', $type]);
    }
    
    public function withUserIdDifferentFrom($user_id)
    {
        return $this->andWhere(['<>', 'user_id', $user_id]);
    }
    
    public function recent($seconds=600)
    {
        return $this->andWhere(['>', 'happened_at', time()-$seconds]);
    }
    

    /**
     * {@inheritdoc}
     * @return Activity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Activity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Petition]].
 *
 * @see Petition
 */
class PetitionQuery extends \yii\db\ActiveQuery
{

    public function active($active=true)
    {
        if ($active) {
            return $this->andWhere(['=', 'wf_status', 'PetitionWorkflow/ongoing']);
        }
        else {
            return $this->andWhere(['<>', 'wf_status', 'PetitionWorkflow/ongoing']);
        }
    }
    
    public function withSlug($slug)
    {
        return $this->andWhere(['=', 'slug', $slug]);
    }

    /**
     * {@inheritdoc}
     * @return Petition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Petition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

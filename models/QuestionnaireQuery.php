<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Questionnaire]].
 *
 * @see Questionnaire
 */
class QuestionnaireQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        if ($active) {
            return $this
                ->andWhere(['=', 'wf_status', 'QuestionnaireWorkflow/published'])
            ;
        }
        else {
            return $this
                ->andWhere(['<>', 'wf_status', 'QuestionnaireWorkflow/published'])
                ;
        }
    }
    
    /**
     * @param int $id The ID of the questionnaire.
     * @return QuestionnaireQuery the active query for a specific ID.
     */
    public function withId($id)
    {
        return $this->andWhere(['id' => $id]);
    }
    
    /**
     * {@inheritdoc}
     * @return Questionnaire[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Questionnaire|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

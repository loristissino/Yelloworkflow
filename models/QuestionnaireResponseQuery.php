<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Questionnaire]].
 *
 * @see Questionnaire
 */
class QuestionnaireResponseQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int $id The ID of the questionnaire.
     * @return QuestionnaireQuery the active query for a specific ID.
     */
    public function withId($id)
    {
        return $this->andWhere(['id' => $id]);
    }
    
    public function filledBy($user_id)
    {
        return $this->andWhere(['user_id' => $user_id]);
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

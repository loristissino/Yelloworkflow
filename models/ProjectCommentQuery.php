<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectComment]].
 *
 * @see ProjectComment
 */
class ProjectCommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    
    public function after($instant)
    {
        return $this->andWhere(['>', 'updated_at', $instant]);
    }

    public function forProject(Project $project)
    {
        return $this->andWhere(['=', 'project_id', $project->id]);
    }

    public function ofUser($user_id)
    {
        return $this->andWhere(['=', 'user_id', $user_id]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }


    /**
     * {@inheritdoc}
     * @return ProjectComment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectComment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

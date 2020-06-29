<?php

namespace app\models;

use yii\db\conditions\OrCondition;

/**
 * This is the ActiveQuery class for [[Project]].
 *
 * @see Project
 */
class ProjectQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        if ($active) {
        return $this
            ->andWhere(['<>', 'wf_status', 'ProjectWorkflow/archived'])
            ->andWhere(['<>', 'wf_status', 'ProjectWorkflow/deleted'])
            ;
        }
        else {
            return $this->andWhere(new OrCondition([
                ['=', 'wf_status', 'ProjectWorkflow/archived'],
                ['=', 'wf_status', 'ProjectWorkflow/deleted']
            ]));
        }
    }
    
    public function draft($draft=true)
    {
        return $this->andWhere([$draft ? '=' : '<>', 'wf_status', 'ProjectWorkflow/draft']);
    }

    public function approved()
    {
        return $this->andWhere(['=', 'wf_status', 'ProjectWorkflow/approved']);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withOrganizationalUnitId($id)
    {
        return $this->andWhere(['=', 'organizational_unit_id', $id]);
    }

    /**
     * {@inheritdoc}
     * @return Project[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Project|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

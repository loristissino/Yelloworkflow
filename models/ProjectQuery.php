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
    
    public function commented()
    {
        return $this->joinWith('projectComments')->andWhere(['<>', 'project_comments.id', 0]);
    }
    
    public function workedOn()
    {
        return $this->joinWith('projectComments')->andWhere(new OrCondition([
                ['<>', 'wf_status', 'ProjectWorkflow/draft'],
                ['<>', 'project_comments.id', 0]
            ]))
        ;
    }

    public function approved($alsoEnded = false)
    {
        $conditions = [
            ['=', 'wf_status', 'ProjectWorkflow/approved'],
            ['=', 'wf_status', 'ProjectWorkflow/partially-approved'],
        ];
        
        if ($alsoEnded) {
            // this is an exception: used for reimbursements, that can occurr when a project is closed or approved
            $conditions[] = ['=', 'wf_status', 'ProjectWorkflow/ended'];
        }
        
        return $this->andWhere(new OrCondition($conditions));
    }

    public function completed()
    {
        $conditions = [
            ['=', 'wf_status', 'ProjectWorkflow/ended'],
            ['=', 'wf_status', 'ProjectWorkflow/reimbursed'],
            ['=', 'wf_status', 'ProjectWorkflow/archived'],
        ];
                
        return $this->andWhere(new OrCondition($conditions));
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'projects.id', $id]);
    }

    public function withOrganizationalUnitId($id)
    {
        if ($id==0) {
            return $this;
        }
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

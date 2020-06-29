<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[OrganizationalUnit]].
 *
 * @see OrganizationalUnit
 */
class OrganizationalUnitQuery extends \yii\db\ActiveQuery
{
    public function active($active=true)
    {
        return $this->andWhere(['=', 'status', $active ? 1 : 0]);
    }

    public function withId($id)
    {
        // we need to specify the table because of the 
        return $this->andWhere(['=', 'organizational_units.id', $id]);
    }
    
    public function withUser($user_id)
    {
        return $this
            ->innerJoinWith('affiliations')
            ->andWhere(['=', 'user_id', $user_id])
            ;
    }
    
    public function withPossibileActions($possibleActions=null)
    {
        $sql = 'possible_actions & ' . $possibleActions . ' = ' . $possibleActions;
        return $possibleActions === null ? $this : $this->andWhere($sql);
    }

    /**
     * {@inheritdoc}
     * @return OrganizationalUnit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrganizationalUnit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

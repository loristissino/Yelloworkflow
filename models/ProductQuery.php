<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }
    
    
    public function salableByOrganizationalUnit($organizationalUnit){
        return $this->andWhere(['or',
            ['=', 'organizational_unit_id', $organizationalUnit->id], 
            ['organizational_unit_id'=>null]
        ]);
    }
    
    /**
     * {@inheritdoc}
     * @return Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

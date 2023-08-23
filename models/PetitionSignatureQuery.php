<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PetitionSignature]].
 *
 * @see PetitionSignature
 */
class PetitionSignatureQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    
    public function ofPetition($petition_id)
    {
        return $this->andWhere(['=', 'petition_id', $petition_id]);
    }

    public function withEmail($email)
    {
        return $this->andWhere(['=', 'email', $email]);
    }
    
    public function withConfirmationCode($code)
    {
        return $this->andWhere(['=', 'confirmation_code', $code]);
    }
    
    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }
    
    public function confirmed($positive=true)
    {
        return $this->andWhere('[[confirmed_at]] IS ' . ($positive? 'NOT' : '') . ' NULL');
    }

    public function validated($positive=true)
    {
        return $this->andWhere('[[validated_at]] IS ' . ($positive? 'NOT' : '') . ' NULL');
    }

    public function reminded($positive=true)
    {
        return $this->andWhere('[[reminded_at]] IS ' . ($positive? 'NOT' : '') . ' NULL');
    }
    
    public function createdBefore($timestamp)
    {
        return $this->andWhere(['<', 'created_at', $timestamp]);
    }

    /**
     * {@inheritdoc}
     * @return PetitionSignature[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PetitionSignature|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

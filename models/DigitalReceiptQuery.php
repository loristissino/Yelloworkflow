<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DigitalReceipt]].
 *
 * @see DigitalReceipt
 */
class DigitalReceiptQuery extends \yii\db\ActiveQuery
{
    /**
    public function active()s
    {
        return $this->andWhere('[[status]]=1');
    }
    */
    
    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withClientId($id)
    {
        return $this->andWhere(['=', 'client_id', $id]);
    }

    public function withAssignedId($id)
    {
        return $this->andWhere(['=', 'assigned_id', $id]);
    }

    public function withOrganizationalUnitId($id)
    {
        //die("called with id: " .$id);
        return $this->andWhere(['=', 'organizational_unit_id', $id]);
    }

    public function linkedToExpo($id)
    {
        return $this->andWhere(['=', 'expo_id', $id]);
    }
    
    public function linkedToTransaction($id)
    {
        return $this->andWhere(['=', 'transaction_id', $id]);
    }

    public function linkedToATransaction()
    {
        return $this->andWhere('transaction_id IS NOT NULL');
    }
    
    public function justIssued()
    {
        // issued and not sent, nor journalized or recorded
        return $this->andWhere(['=', 'wf_status', 'DigitalReceiptWorkflow/issued']);
    }
    
    public function justSent()
    {
        // sent and not yet journalized or recorded
        return $this
            ->andWhere(['=', 'wf_status', 'DigitalReceiptWorkflow/sent'])
            ->andWhere(['<', 'sent_at', time() - 5])
        ;
    }

    public function createdBefore($time)
    {
        return $this->andWhere(['<', 'created_at', $time]);
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

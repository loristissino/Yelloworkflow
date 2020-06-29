<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @see Account
 */
class AccountQuery extends \yii\db\ActiveQuery
{

    public function active($active=true)
    {
        return $this->andWhere(['=', 'accounts.status', $active ? 1 : 0]);
    }

    public function withId($id)
    {
        return $this->andWhere(['=', 'id', $id]);
    }

    public function withCode($code)
    {
        return $this->andWhere(['=', 'code', $code]);
    }

    public function withOrganizationalUnitId($id)
    {
        return $this->andWhere(['=', 'organizational_unit_id', $id]);
    }

    public function shownInOUView()
    {
        return $this->andWhere(['>', 'accounts.shown_in_ou_view', 0]);
    }

    /**
     * {@inheritdoc}
     * @return Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransactionTemplatePosting;

/**
 * TransactionTemplatePostingSearch represents the model behind the search form of `\app\models\TransactionTemplatePosting`.
 */
class TransactionTemplatePostingSearch extends TransactionTemplatePosting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transaction_template_id', 'rank', 'account_id'], 'integer'],
            [['dc'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $query=null)
    {
        $query = $query ? $query : TransactionTemplatePosting::find();

        // add conditions that should always apply here
        $query
        ->joinWith('account')
        ;        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'transaction_template_id' => $this->transaction_template_id,
            'rank' => $this->rank,
            'account_id' => $this->account_id,
        ]);

        $query->andFilterWhere(['like', 'dc', $this->dc]);

        return $dataProvider;
    }
}

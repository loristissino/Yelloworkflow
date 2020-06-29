<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Account;

/**
 * AccountSearch represents the model behind the search form of `\app\models\Account`.
 */
class AccountSearch extends Account
{

    public $organizational_unit;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'organizational_unit_id', 'rank', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code', 'organizational_unit', 'enforced_balance'], 'safe'],
            [['represents', 'enforced_balance'], 'string', 'max'=>1],
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
        $query = $query ? $query : Account::find();

        // add conditions that should always apply here
        
        $query
        ->joinWith('organizationalUnit')
        ;           

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['organizational_unit'] = [  
            'asc' => ['organizational_units.name' => SORT_ASC],
            'desc' => ['organizational_units.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'organizational_unit_id' => $this->organizational_unit_id,
            'rank' => $this->rank,
            'status' => $this->status,
            'represents' => $this->represents,
            'enforced_balance' => $this->enforced_balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        $query
            ->andFilterWhere(['like', 'organizational_units.name', $this->organizational_unit]);

        return $dataProvider;
    }
}

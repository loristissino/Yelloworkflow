<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransactionTemplate;

/**
 * TransactionTemplateSearch represents the model behind the search form of `\app\models\TransactionTemplate`.
 */
class TransactionTemplateSearch extends TransactionTemplate
{

    public $organizational_unit;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'organizational_unit_id', 'status', 'rank', 'needs_attachment', 'needs_project', 'needs_vendor'], 'integer'],
            [['title', 'description', 'organizational_unit'], 'safe'],
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
        $query = TransactionTemplate::find();
        $query = $query ? $query : TransactionTemplate::find();

        // add conditions that should always apply here

        $query
        ->joinWith('organizationalUnit')
        ->joinWith('accounts');
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
            'status' => $this->status,
            'rank' => $this->rank,
            'needs_vendor' => $this->needs_vendor,
            'needs_attachment' => $this->needs_attachment,
            'needs_project' => $this->needs_project,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'organizational_units.name', $this->organizational_unit])
            ;

        return $dataProvider;
    }
}

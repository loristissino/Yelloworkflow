<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `\app\models\Project`.
 */
class ProjectSearch extends Project
{
    public $organizational_unit;
    public $wf_status;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'organizational_unit_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'co_hosts', 'partners', 'period', 'place', 'wf_status', 'organizational_unit'], 'safe'],
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
        $query = $query ? $query : Project::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'co_hosts', $this->co_hosts])
            ->andFilterWhere(['like', 'partners', $this->partners])
            ->andFilterWhere(['like', 'period', $this->period])
            ->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'wf_status', $this->wf_status]);

        $query
            ->andFilterWhere(['like', 'organizational_units.name', $this->organizational_unit])
            ;



        return $dataProvider;
    }
}

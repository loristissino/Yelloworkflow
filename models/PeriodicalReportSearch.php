<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PeriodicalReport;

/**
 * PeriodicalReportSearch represents the model behind the search form of `\app\models\PeriodicalReport`.
 */
class PeriodicalReportSearch extends PeriodicalReport
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
            [['name', 'begin_date', 'end_date', 'wf_status', 'organizational_unit'], 'safe'],
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
        $query = $query ? $query : PeriodicalReport::find();
        
        $query
        ->joinWith('organizationalUnit')
        ;      

        // add conditions that should always apply here

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
            'begin_date' => $this->begin_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'wf_status', $this->wf_status]);

        $query
            ->andFilterWhere(['like', 'organizational_units.name', $this->organizational_unit])
            ;


        return $dataProvider;
    }
}

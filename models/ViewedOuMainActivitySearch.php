<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ViewedOuMainActivity;

/**
 * MainOuViewedActivitySearch represents the model behind the search form of `app\models\MainOuViewedActivity`.
 */
class ViewedOuMainActivitySearch extends ViewedOuMainActivity
{
    public $daysBack;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'happened_at', 'user_id', 'organizational_unit_id', 'role_id', 'daysBack'], 'integer'],
            [['activity_type', 'first_name', 'last_name', 'name', 'role_description', 'organizational_unit_id'], 'safe'],
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
    public function search($params)
    {
        $query = ViewedOuMainActivity::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                /*
                'attributes' => [
                    'happened_at',
                    'activity_type',
                    'first_name',
                    'last_name',
                    'organizational_unit_id',
                    'name',
                    'role_description',
                    ],*/
                'defaultOrder' => [
                    'happened_at' => SORT_DESC,
                ],
            ],
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
            'user_id' => $this->user_id,
            'organizational_unit_id' => $this->organizational_unit_id,
            'role_id' => $this->role_id,
        ]);

        $query->andFilterWhere(['like', 'activity_type', $this->activity_type])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'role_description', $this->role_description])
            ->andFilterWhere(['>=', 'happened_at', time() - $params['daysBack']*24*60*60]);

        return $dataProvider;
    }
}

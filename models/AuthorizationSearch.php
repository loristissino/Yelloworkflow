<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Authorization;

/**
 * AuthorizationSearch represents the model behind the search form of `\app\models\Authorization`.
 */
class AuthorizationSearch extends Authorization
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'role_id', 'created_at', 'updated_at'], 'integer'],
            [['controller_id', 'action_id', 'method', 'type', 'begin_date', 'end_date'], 'safe'],
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
    public function search($params, $query)
    {
        $query = $query ? $query : ___________::find();

        // add conditions that should always apply here

        $query
        ->joinWith('user')
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
            'authorizations.id' => $this->id,
            'user_id' => $this->user_id,
            'begin_date' => $this->begin_date,
            'end_date' => $this->end_date,
            'role_id' => $this->role_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'controller_id', $this->controller_id])
            ->andFilterWhere(['like', 'action_id', $this->action_id])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}

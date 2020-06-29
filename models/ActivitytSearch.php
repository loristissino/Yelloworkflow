<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Activity;
use yii\db\conditions\OrCondition;

/**
 * ActivitytSearch represents the model behind the search form of `\app\models\Activity`.
 */
class ActivitytSearch extends Activity
{

    public $full_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'model_id', 'authorization_id', 'happened_at'], 'integer'],
            [['full_name', 'activity_type', 'model'], 'safe'],
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
        $query = $query ? $query : Activity::find();
        
        $query
        ->joinWith('user')
        ->joinWith('authorization')
        ;    

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['full_name'] = [  
            'asc' => ['users.last_name' => SORT_ASC, 'users.first_name' => SORT_ASC],
            'desc' => ['users.last_name' => SORT_DESC, 'users.first_name' => SORT_ASC],
        ];

        $this->load($params);
        
        if (!$this->full_name) {
            $this->full_name = '';
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'model_id' => $this->model_id,
            'authorization_id' => $this->authorization_id,
            'happened_at' => $this->happened_at,
        ]);

        $query->andFilterWhere(['like', 'activity_type', $this->activity_type])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'info', $this->info]);

        $query
            ->andWhere(new OrCondition([
                ['like', 'users.first_name', $this->full_name],
                ['like', 'users.last_name', $this->full_name]
                ]
            ))
            ;


        return $dataProvider;
    }
}

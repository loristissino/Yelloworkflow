<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Affiliation;
use yii\db\conditions\OrCondition;

/**
 * AffiliationSearch represents the model behind the search form of `\app\models\Affiliation`.
 */
class AffiliationSearch extends Affiliation
{
    public $full_name;
    public $organizational_unit;
    public $role;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id', 'user_id', 'organizational_unit_id', 'role_id', 'rank'], 'integer'],
            // [['first_name', 'last_name', 'full_name', 'organizational_unit', 'role'], 'safe'],        
            [['full_name', 'organizational_unit', 'role', 'email'], 'safe'],        
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
        $query = $query ? $query : Affiliation::find();

        // add conditions that should always apply here
        
        $query
        ->joinWith('user')
        ->joinWith('organizationalUnit')
        ->joinWith('role')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /*
        $dataProvider->sort->attributes['first_name'] = [  
            'asc' => ['users.first_name' => SORT_ASC],
            'desc' => ['users.first_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['last_name'] = [  
            'asc' => ['users.last_name' => SORT_ASC],
            'desc' => ['users.last_name' => SORT_DESC],
        ];
        */
        $dataProvider->sort->attributes['organizational_unit'] = [  
            'asc' => ['organizational_units.name' => SORT_ASC],
            'desc' => ['organizational_units.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['role'] = [  
            'asc' => ['roles.name' => SORT_ASC],
            'desc' => ['roles.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['full_name'] = [  
            'asc' => ['users.last_name' => SORT_ASC, 'users.first_name' => SORT_ASC],
            'desc' => ['users.last_name' => SORT_DESC, 'users.first_name' => SORT_ASC],
        ];
        $dataProvider->sort->attributes['email'] = [  
            'asc' => ['email' => SORT_ASC],
            'desc' => ['email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            /*
            'id' => $this->id,
            'user_id' => $this->user_id,
            'organizational_unit_id' => $this->organizational_unit_id,
            'role_id' => $this->role_id,
            'rank' => $this->rank,
            */
        ]);
        
        if (!$this->full_name) {
            $this->full_name = '';
        }
        
        $query
            //->andFilterWhere(['like', 'users.first_name', $this->first_name])
            //->andFilterWhere(['like', 'users.last_name', $this->last_name])
            
            ->andWhere(new OrCondition([
                ['like', 'users.first_name', $this->full_name],
                ['like', 'users.last_name', $this->full_name]
                ]
            ))
            ->andFilterWhere(['like', 'organizational_units.name', $this->organizational_unit])
            ->andFilterWhere(['like', 'roles.description', $this->role])
            ->andFilterWhere(['like', 'email', $this->email])
            ;

        return $dataProvider;
    }
}

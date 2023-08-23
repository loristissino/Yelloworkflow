<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PetitionSignature;

/**
 * PetitionSignatureSearch represents the model behind the search form of `app\models\PetitionSignature`.
 */
class PetitionSignatureSearch extends PetitionSignature
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'petition_id', 'created_at', 'updated_at', 'confirmed_at', 'validated_at'], 'integer'],
            [['email', 'first_name', 'last_name', 'message', 'accepted_terms'], 'safe'],
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
        $query = PetitionSignature::find();

        // add conditions that should always apply here

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
            'petition_id' => $this->petition_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'confirmed_at' => $this->confirmed_at,
            'validated_at' => $this->validated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'message', $this->message])
//            ->andFilterWhere(['like', 'accepted_terms', $this->accepted_terms])
            ;

        return $dataProvider;
    }
}

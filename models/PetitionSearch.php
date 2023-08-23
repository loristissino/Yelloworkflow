<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Petition;

/**
 * PetitionSearch represents the model behind the search form of `app\models\Petition`.
 */
class PetitionSearch extends Petition
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'launched_at', 'expired_at', 'goal'], 'integer'],
            [['slug', 'title', 'target', 'introduction', 'picture_url', 'request', 'updates', 'wf_status'], 'safe'],
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
        $query = Petition::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'launched_at' => $this->launched_at,
            'expired_at' => $this->expired_at,
            'goal' => $this->goal,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'introduction', $this->introduction])
            ->andFilterWhere(['like', 'picture_url', $this->picture_url])
            ->andFilterWhere(['like', 'request', $this->request])
            ->andFilterWhere(['like', 'updates', $this->updates])
            ->andFilterWhere(['like', 'wf_status', $this->wf_status]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Questionnaire;

/**
 * QuestionnaireSearch represents the model behind the search form of `app\models\Questionnaire`.
 */
class QuestionnaireSearch extends Questionnaire
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'version', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'wf_status', 'definition'], 'safe'],
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
        $query = Questionnaire::find();

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
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'wf_status', $this->wf_status])
            ->andFilterWhere(['like', 'definition', $this->definition]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reimbursement;

/**
 * ReimbursementSearch represents the model behind the search form of `\app\models\Reimbursement`.
 */
class ReimbursementSearch extends Reimbursement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'created_at', 'updated_at'], 'integer'],
            [['wf_status', 'request_description', 'reimbursement_description'], 'safe'],
            [['requested_amount', 'reimbursed_amount'], 'number'],
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
        $query = Reimbursement::find();

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
            'project_id' => $this->project_id,
            'requested_amount' => $this->requested_amount,
            'reimbursed_amount' => $this->reimbursed_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'wf_status', $this->wf_status])
            ->andFilterWhere(['like', 'request_description', $this->request_description])
            ->andFilterWhere(['like', 'reimbursement_description', $this->reimbursement_description]);

        return $dataProvider;
    }
}

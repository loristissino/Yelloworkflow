<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DigitalReceipt;

/**
 * DigitalReceiptSearch represents the model behind the search form of `app\models\DigitalReceipt`.
 */
class DigitalReceiptSearch extends DigitalReceipt
{
    public $organizational_unit;
    public $wf_status;
    public $sequential_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'organizational_unit_id', 'digital_receipt_type_id', 'created_at', 'updated_at', 'sent_at', 'transaction_id'], 'integer'],
            [['date', 'wf_status', 'tags', 'email', 'phone', 'client_id', 'assigned_id', 'document_number', 'api_response', 'sequential_number'], 'safe'],
            [['total_amount', 'cash_payment_amount', 'electronic_payment_amount'], 'number'],
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
        $query = $query ? $query : DigitalReceipt::find();

        // add conditions that should always apply here
        
        $query
        ->joinWith('organizationalUnit')
        ->joinWith('digitalReceiptType')
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
            'date' => $this->date,
            'user_id' => $this->user_id,
            'organizational_unit_id' => $this->organizational_unit_id,
            'digital_receipt_type_id' => $this->digital_receipt_type_id,
            'total_amount' => $this->total_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sent_at' => $this->sent_at,
            'transaction_id' => $this->transaction_id,
            'cash_payment_amount' => $this->cash_payment_amount,
            'electronic_payment_amount' => $this->electronic_payment_amount,
        ]);

        $query->andFilterWhere(['like', 'wf_status', $this->wf_status])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'client_id', $this->client_id])
            ->andFilterWhere(['like', 'assigned_id', $this->assigned_id])
            ->andFilterWhere(['like', 'document_number', $this->document_number])
            ->andFilterWhere(['like', 'api_response', $this->api_response]);

        $query->andFilterWhere(['=', 'RIGHT(wf_status, ' . strlen($this->wf_status) . ')', $this->wf_status])
            ->andFilterWhere(['like', 'organizational_units.name', $this->organizational_unit])
            ;
        
        if ($this->sequential_number) {
            // Check if input matches the complete format (e.g., "2026-00123/ABC")
            if (preg_match('/^(\d{4})-(\d+)\/(.+)$/', $this->sequential_number, $matches)) {
                $year = $matches[1];
                $number = (int)$matches[2]; // Remove leading zeros
                $code = $matches[3];
                
                $query->andFilterWhere(['=', 'date_format(date, "%Y")', $year])
                    ->andFilterWhere(['=', 'sequential_number', $number])
                    ->andFilterWhere(['=', 'digital_receipt_types.sequential_number_code', $code]);
            } else {
                // Fallback: search just the number part (user enters "123" to find "00123")
                $query->andFilterWhere(['like', 'sequential_number', $this->sequential_number]);
            }
        }
        

        return $dataProvider;
    }
}

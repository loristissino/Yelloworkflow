<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DigitalReceiptLine;

/**
 * DigitalReceiptLineSearch represents the model behind the search form of `app\models\DigitalReceiptLine`.
 */
class DigitalReceiptLineSearch extends DigitalReceiptLine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'digital_receipt_id', 'product_id', 'quantity'], 'integer'],
            [['sku', 'description', 'vat_rate_code', 'notes'], 'safe'],
            [['unit_price', 'discount', 'amount'], 'number'],
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
        $query = $query ? $query : DigitalReceiptLine::find();

        // add conditions that should always apply here

        $query
            ->joinWith('digitalReceipt')
            ->joinWith('digitalReceipt.digitalReceiptType')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->defaultOrder = ['digital_receipt_id'=>SORT_ASC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'digital_receipt_id' => $this->digital_receipt_id,
            'product_id' => $this->product_id,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'vat_rate_code', $this->vat_rate_code])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}

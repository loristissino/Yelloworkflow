<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'digital_receipt_type_id', 'organizational_unit_id', 'status', 'rank'], 'integer'],
            [['sku', 'isbn', 'author', 'description', 'url', 'vat_rate_code', 'notes'], 'safe'],
            [['unit_price', 'max_discount', 'standard_discount', 'internal_discount'], 'number'],
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
        $query = $query ? $query : Product::find();
        
        $query
            ->joinWith('organizationalUnit')
        ;     

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
            'digital_receipt_type_id' => $this->digital_receipt_type_id,
            'organizational_unit_id' => $this->organizational_unit_id,
            'status' => $this->status,
            'unit_price' => $this->unit_price,
            'standard_discount' => $this->standard_discount,
            'internal_discount' => $this->internal_discount,
            'rank' => $this->rank,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'vat_rate_code', $this->vat_rate_code])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}

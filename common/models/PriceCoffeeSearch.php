<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PriceCoffee;

/**
 * PriceCoffeeSearch represents the model behind the search form of `common\models\PriceCoffee`.
 */
class PriceCoffeeSearch extends PriceCoffee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price_average', 'unit', 'updated_at'], 'integer'],
            [['created_at','province_id','organisation_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = PriceCoffee::find();

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
            'price_average' => $this->price_average,
            'unit' => $this->unit,
            'updated_at' => $this->updated_at,
        ]);
        if ($this->created_at !== '' && $this->created_at !== null) {
            $from_time = strtotime(str_replace('/', '-', $this->created_at) . ' 00:00:00');
            $to_time = strtotime(str_replace('/', '-', $this->created_at) . ' 23:59:59');
            $query->andFilterWhere(['>=', 'created_at', $from_time]);
            $query->andFilterWhere(['<=', 'created_at', $to_time]);
        }
        $query->andFilterWhere(['like','province_id',$this->province_id]);
        $query->andFilterWhere(['like','organisation_name',$this->organisation_name]);


        return $dataProvider;
    }
}

<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExchangeBuy;

/**
 * ExchangeBuySearch represents the model behind the search form of `common\models\ExchangeBuy`.
 */
class ExchangeBuySearch extends ExchangeBuy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subscriber_id', 'created_at', 'updated_at'], 'integer'],
            [['price_buy', 'total_quantity'], 'safe'],
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
        $query = ExchangeBuy::find();

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
            'subscriber_id' => $this->subscriber_id,
            'updated_at' => $this->updated_at,
        ]);
        if ($this->created_at !== '' && $this->created_at !== null) {
            $from_time = strtotime(str_replace('/', '-', $this->created_at) . ' 00:00:00');
            $to_time = strtotime(str_replace('/', '-', $this->created_at) . ' 23:59:59');
            $query->andFilterWhere(['>=', 'created_at', $from_time]);
            $query->andFilterWhere(['<=', 'created_at', $to_time]);
        }
        $query->andFilterWhere(['like', 'price_buy', $this->price_buy])
            ->andFilterWhere(['like', 'total_quantity', $this->total_quantity]);

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = ExchangeBuy::find()->orderBy(['created_at'=>SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->created_at !== '' && $this->created_at !== null) {
            $from_time = strtotime(str_replace('/', '-', $this->created_at) . ' 00:00:00');
            $to_time = strtotime(str_replace('/', '-', $this->created_at) . ' 23:59:59');
            $query->andFilterWhere(['>=', 'created_at', $from_time]);
            $query->andFilterWhere(['<=', 'created_at', $to_time]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }


}

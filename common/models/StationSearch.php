<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Station;

/**
 * StationSearch represents the model behind the search form of `common\models\Station`.
 */
class StationSearch extends Station
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'station_code', 'province_id','status'], 'integer'],
            [['station_name', 'url_weather', 'latitude', 'longtitude'], 'safe'],
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
        $query = Station::find();

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
            'station_code' => $this->station_code,
            'province_id' => $this->province_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'station_name', $this->station_name])
            ->andFilterWhere(['like', 'url_weather', $this->url_weather])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longtitude', $this->longtitude]);

        return $dataProvider;
    }
}

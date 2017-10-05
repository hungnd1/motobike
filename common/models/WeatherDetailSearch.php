<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WeatherDetail;

/**
 * WeatherDetailSearch represents the model behind the search form of `common\models\WeatherDetail`.
 */
class WeatherDetailSearch extends WeatherDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'precipitation', 'tmax', 'tmin', 'wnddir', 'wndspd', 'station_id', 'timestamp', 'created_at', 'updated_at', 'clouddc', 'hprcp', 'RFTMAX', 'RFTMIN'], 'integer'],
            [['station_code'], 'safe'],
            [['hsun'], 'number'],
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
        $today = strtotime('today midnight') + 7 * 60 * 60;
        $tomorrow = strtotime('tomorrow') + 7 * 60 * 60;
        $query = WeatherDetail::find()
            ->andWhere(['>=', 'timestamp', $today])
            ->andWhere(['<', 'timestamp', $tomorrow]);

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
            'precipitation' => $this->precipitation,
            'tmax' => $this->tmax,
            'tmin' => $this->tmin,
            'wnddir' => $this->wnddir,
            'wndspd' => $this->wndspd,
            'station_id' => $this->station_id,
            'timestamp' => $this->timestamp,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'clouddc' => $this->clouddc,
            'hprcp' => $this->hprcp,
            'hsun' => $this->hsun,
            'RFTMAX' => $this->RFTMAX,
            'RFTMIN' => $this->RFTMIN,
        ]);

        $query->andFilterWhere(['like', 'station_code', $this->station_code]);

        return $dataProvider;
    }
}

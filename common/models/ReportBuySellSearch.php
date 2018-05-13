<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ReportBuySell;

/**
 * ReportBuySellSearch represents the model behind the search form of `common\models\ReportBuySell`.
 */
class ReportBuySellSearch extends ReportBuySell
{
    /**
     * @inheritdoc
     */
    public $to_date;
    public $from_date;
    public function rules()
    {
        return [
            [['id', 'province_id', 'type_coffee', 'total_buy', 'total_sell', 'report_date'], 'integer'],
            [['from_date', 'to_date','province_id'], 'safe'],
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
    public function search($params, $page)
    {
        $query = \api\models\ReportBuySell::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['report_date' => SORT_DESC],
            ],
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
            'province_id' => $this->province_id,
            'type_coffee' => $this->type_coffee,
            'total_buy' => $this->total_buy,
            'total_sell' => $this->total_sell,
        ]);
        if ($this->from_date !== '' && $this->from_date !== null && $this->to_date !== '' && $this->to_date !== null) {
            $from_time = strtotime(str_replace('/', '-', $this->from_date) . ' 00:00:00');
            $to_time = strtotime(str_replace('/', '-', $this->to_date) . ' 23:59:59');
            $query->andFilterWhere(['>=', 'report_date', $from_time]);
            $query->andFilterWhere(['<=', 'report_date', $to_time]);
        }

        return $dataProvider;
    }

    public function searchReport($params)
    {
        $query = \api\models\ReportBuySell::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['report_date' => SORT_DESC],
            ],
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
            'province_id' => $this->province_id,
            'type_coffee' => $this->type_coffee,
            'total_buy' => $this->total_buy,
            'total_sell' => $this->total_sell,
        ]);
        if ($this->from_date !== '' && $this->from_date !== null && $this->to_date !== '' && $this->to_date !== null) {
            $query->andFilterWhere(['>=', 'report_date', $this->from_date]);
            $query->andFilterWhere(['<=', 'report_date', $this->to_date]);
        }

        return $dataProvider;
    }

    public function searchReportAll($params)
    {
        $query = \api\models\ReportBuySell::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['report_date' => SORT_DESC],
            ],
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
            'province_id' => $this->province_id,
            'type_coffee' => $this->type_coffee,
            'total_buy' => $this->total_buy,
            'total_sell' => $this->total_sell,
        ]);
        if ($this->from_date !== '' && $this->from_date !== null && $this->to_date !== '' && $this->to_date !== null) {
            $query->andFilterWhere(['>=', 'report_date', $this->from_date]);
            $query->andFilterWhere(['<=', 'report_date', $this->to_date]);
        }

        return $dataProvider;
    }
}

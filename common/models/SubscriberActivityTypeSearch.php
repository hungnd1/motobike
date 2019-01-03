<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SubscriberActivityType;

/**
 * SubscriberActivityTypeSearch represents the model behind the search form of `common\models\SubscriberActivityType`.
 */
class SubscriberActivityTypeSearch extends SubscriberActivityType
{
    /**
     * @inheritdoc
     */
    public $from_date;
    public $to_date;

    public function rules()
    {
        return [
            [['id', 'report_date', 'weather', 'price', 'gap', 'buy', 'gap_disease',
                'qa', 'tracuusuco', 'nongnghiepthongminh', 'biendoikhihau', 'tuvansudungphanbon'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
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
        $query = SubscriberActivityType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'report_date' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->select('report_date,
                        weather,
                        price,biendoikhihau,tuvansudungphanbon,
                        gap,buy,gap_disease,qa,tracuusuco,nongnghiepthongminh '
        );


        if ($this->from_date) {
            $query->andFilterWhere(['>=', 'report_date', $this->from_date]);
        }
        if ($this->to_date) {
            $query->andFilterWhere(['<=', 'report_date', $this->to_date]);
        }
        $query->groupBy('report_date');
        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = SubscriberActivityType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'report_date' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->select('report_date,
                        weather,
                        price,biendoikhihau,tuvansudungphanbon,
                        gap,buy,gap_disease,qa,tracuusuco,nongnghiepthongminh '
        );

        if ($this->from_date) {
            $query->andFilterWhere(['>=', 'report_date', $this->from_date]);
        }
        if ($this->to_date) {
            $query->andFilterWhere(['<=', 'report_date', $this->to_date]);
        }

        $query->groupBy('report_date');
        return $dataProvider;
    }
}

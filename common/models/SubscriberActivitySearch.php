<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SubscriberActivitySearch represents the model behind the search form about `common\models\SubscriberActivity`.
 */
class SubscriberActivitySearch extends SubscriberActivity
{
    public $from_date;
    public $to_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subscriber_id', 'action', 'created_at', 'status', 'target_id', 'target_type', 'type', 'channel'], 'integer'],
            [['msisdn', 'params', 'ip_address', 'description', 'user_agent', 'from_date', 'to_date'], 'safe'],
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
        $query = SubscriberActivity::find()
            ->andWhere(['<>', 'action', 0])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'subscriber_id' => $this->subscriber_id,
            'action' => $this->action,
//            'created_at' => $this->created_at,
            'status' => $this->status,
            'target_id' => $this->target_id,
            'target_type' => $this->target_type,
            'type' => $this->type,
            'channel' => $this->channel,
        ]);

        $query->andFilterWhere(['like', 'msisdn', $this->msisdn])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

//        if($this->created_at){
//            $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_at)]);
//        }

        if ($this->from_date) {
            $query->andWhere(['>=', 'created_at', $this->from_date]);
        }
        if ($this->to_date) {
            $query->andWhere(['<=', 'created_at', $this->to_date]);
        }

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = SubscriberActivity::find()
            ->andWhere(['<>', 'action', 0])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'subscriber_id' => $this->subscriber_id,
            'action' => $this->action,
//            'created_at' => $this->created_at,
            'status' => $this->status,
            'target_id' => $this->target_id,
            'target_type' => $this->target_type,
            'type' => $this->type,
            'channel' => $this->channel,
        ]);

        $query->andFilterWhere(['like', 'msisdn', $this->msisdn])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

//        if($this->created_at){
//            $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_at)]);
//        }

        if ($this->from_date) {
            $query->andWhere(['>=', 'created_at', $this->from_date]);
        }
        if ($this->to_date) {
            $query->andWhere(['<=', 'created_at', $this->to_date]);
        }
        return $dataProvider;
    }
}

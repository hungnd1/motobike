<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subscriber;

/**
 * SubscriberSearch represents the model behind the search form of `common\models\Subscriber`.
 */
class SubscriberSearch extends Subscriber
{
    public $from_date;
    public $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'authen_type', 'status'], 'integer'],
            [['username', 'email','created_at'], 'safe'],
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
        $query = Subscriber::find()->orderBy(['created_at'=>SORT_ASC]);

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
            'authen_type' => $this->authen_type,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ]);
        if ($this->created_at !== '' && $this->created_at !== null) {
            $from_time = strtotime(str_replace('/', '-', $this->created_at) . ' 00:00:00');
            $to_time = strtotime(str_replace('/', '-', $this->created_at) . ' 23:59:59');
            $query->andFilterWhere(['>=', 'created_at', $from_time]);
            $query->andFilterWhere(['<=', 'created_at', $to_time]);
        }
        $query
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password]);
        if ($this->from_date) {
            $query->andFilterWhere(['>=', 'created_at', $this->from_date]);
        }
        if ($this->to_date) {
            $query->andFilterWhere(['<=', 'created_at', $this->to_date]);
        }

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = Subscriber::find()->orderBy(['created_at'=>SORT_ASC]);

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'authen_type' => $this->authen_type,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}

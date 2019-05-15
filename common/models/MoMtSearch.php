<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MoMt;

/**
 * MoMtSearch represents the model behind the search form of `common\models\MoMt`.
 */
class MoMtSearch extends MoMt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'request_id', 'status_sync', 'status', 'created_at', 'updated_at'], 'integer'],
            [['from_number', 'to_number', 'message_mo', 'message_mt'], 'safe'],
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
        $query = MoMt::find();

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
            'request_id' => $this->request_id,
            'status_sync' => $this->status_sync,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'from_number', $this->from_number])
            ->andFilterWhere(['like', 'to_number', $this->to_number])
            ->andFilterWhere(['like', 'message_mo', $this->message_mo])
            ->andFilterWhere(['like', 'message_mt', $this->message_mt]);

        return $dataProvider;
    }
}

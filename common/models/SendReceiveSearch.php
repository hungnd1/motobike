<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SendReceive;

/**
 * SendReceiveSearch represents the model behind the search form of `common\models\SendReceive`.
 */
class SendReceiveSearch extends SendReceive
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status', 'error_code'], 'integer'],
            [['from', 'to', 'text', 'carrier', 'description'], 'safe'],
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
        $query = SendReceive::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'error_code' => $this->error_code,
        ]);

        $query->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'carrier', $this->carrier])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

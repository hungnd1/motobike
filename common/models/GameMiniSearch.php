<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GameMini;

/**
 * GameMiniSearch represents the model behind the search form of `common\models\GameMini`.
 */
class GameMiniSearch extends GameMini
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['question', 'answer_a', 'answer_b', 'answer_c', 'answer_d', 'answer_correct'], 'safe'],
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
        $query = GameMini::find();

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
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'answer_a', $this->answer_a])
            ->andFilterWhere(['like', 'answer_b', $this->answer_b])
            ->andFilterWhere(['like', 'answer_c', $this->answer_c])
            ->andFilterWhere(['like', 'answer_d', $this->answer_d])
            ->andFilterWhere(['like', 'answer_correct', $this->answer_correct]);

        return $dataProvider;
    }
}

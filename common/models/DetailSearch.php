<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Detail;

/**
 * DetailSearch represents the model behind the search form of `common\models\Detail`.
 */
class DetailSearch extends Detail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'feature_id', 'group_id', 'fruit_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['display_name', 'description', 'reason', 'harm', 'prevention', 'image'], 'safe'],
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
        $query = Detail::find();

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
            'feature_id' => $this->feature_id,
            'group_id' => $this->group_id,
            'fruit_id' => $this->fruit_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'display_name', $this->display_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'harm', $this->harm])
            ->andFilterWhere(['like', 'prevention', $this->prevention])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}

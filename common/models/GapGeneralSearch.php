<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GapGeneralSearch represents the model behind the search form of `common\models\GapGeneral`.
 */
class GapGeneralSearch extends GapGeneral
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at','fruit_id','category_id'], 'integer'],
            [['gap', 'title'], 'safe'],
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
    public function search($params, $type)
    {
        $query = GapGeneral::find()->andWhere(['type' => $type])->orderBy(['created_at'=>SORT_DESC]);

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fruit_id' => $this->fruit_id,
            'category_id' => $this->category_id
        ]);

        $query->andFilterWhere(['like', 'gap', $this->gap]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}

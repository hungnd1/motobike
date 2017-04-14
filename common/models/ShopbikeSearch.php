<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShopbikeSearch represents the model behind the search form of `common\models\Shopbike`.
 */
class ShopbikeSearch extends Shopbike
{
    /**
     * @inheritdoc
     */

    public $keyword;

    public function rules()
    {
        return [
            [['id', 'like_count', 'rating_count', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password', 'password_hash', 'email', 'phone', 'address', 'keyword', 'facebook_id', 'avatar'], 'safe'],
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
        $query = Shopbike::find();

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
        $query->andFilterWhere(['=', 'status', $this->status]);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);
        return $dataProvider;
    }
}

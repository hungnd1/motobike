<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */

    public $keyword;
    public $categoryIds;
    public $listCatIds;
    public $category_id;
    public $order;

    public function rules()
    {
        return [
            [['id', 'type', 'status', 'price', 'price_promotion', 'category_id', 'created_at', 'updated_at', 'approved_at', 'like_count', 'comment_count', 'is_free'], 'integer'],
            [['display_name', 'ascii_name', 'code', 'short_description','categoryIds','keyword', 'description', 'images'], 'safe'],
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
        $query = Product::find();
        $inactiveCategory = ArrayHelper::map(Category::findAll(['status' => Category::STATUS_INACTIVE]), 'display_name', 'id');

        $query->innerJoin('product_category_asm', 'product.id = product_category_asm.product_id');
        $query->andWhere(['NOT IN', 'product_category_asm.category_id', $inactiveCategory]);
        $query->andWhere('product.status!= :p_status_delete', [':p_status_delete' => Product::STATUS_DELETE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->categoryIds) {
            $categoryIds = explode(',', $this->categoryIds);
            //     $this->listCatIds = $categoryIds;

            // $query->distinct();

            $query->andWhere(['IN', 'product_category_asm.category_id', $categoryIds]);
        }
        if ($this->keyword) {
            $query->andFilterWhere(['like', 'product.display_name', $this->keyword]);
        }
        $query->andFilterWhere(['like', 'product.display_name', $this->display_name]);
        $query->andFilterWhere(['=', 'product.status', $this->status]);

        if ($this->created_at !== '') {
            $create_at = $this->created_at;
            $create_at_end = $this->created_at + (60 * 60 * 24);
            $query->andFilterWhere(['>=', 'product.created_at', $create_at]);
            $query->andFilterWhere(['<=', 'product.created_at', $create_at_end]);
        }
        return $dataProvider;
    }
}

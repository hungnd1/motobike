<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_category_asm".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductCategoryAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category_asm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

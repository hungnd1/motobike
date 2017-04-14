<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_shopbike_asm".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $shopbike_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductShopbikeAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_shopbike_asm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'shopbike_id'], 'required'],
            [['product_id', 'shopbike_id', 'created_at', 'updated_at'], 'integer'],
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
            'shopbike_id' => 'Shopbike ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exchange_buy".
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property string $price_buy
 * @property string $total_quantity
 * @property integer $created_at
 * @property integer $type_coffee_id
 * @property integer $updated_at
 */
class ExchangeBuy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchange_buy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_id', 'created_at', 'updated_at','type_coffee_id'], 'integer'],
            [['price_buy', 'total_quantity'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber_id' => 'Subscriber ID',
            'price_buy' => 'Price Buy',
            'total_quantity' => 'Total Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exchange_farmer".
 *
 * @property integer $id
 * @property double $quanlity
 * @property double $sold
 * @property double $desire_to_sell
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $subscriber_id
 * @property integer $type_coffee
 */
class ExchangeFarmer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchange_farmer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quanlity', 'sold', 'desire_to_sell'], 'number'],
            [['created_at', 'updated_at', 'subscriber_id', 'type_coffee'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quanlity' => 'Quanlity',
            'sold' => 'Sold',
            'desire_to_sell' => 'Desire To Sell',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'subscriber_id' => 'Subscriber ID',
            'type_coffee' => 'Type Coffee',
        ];
    }
}

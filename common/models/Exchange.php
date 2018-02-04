<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exchange".
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property integer $type_coffee
 * @property string $location
 * @property string $location_name
 * @property string $price
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $province_id
 * @property string $total_quantity
 */
class Exchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_id','province_id', 'type_coffee', 'created_at', 'updated_at'], 'integer'],
            [['location','price','location_name','total_quantity'], 'string', 'max' => 255],
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
            'total_quantity' => 'Tổng sản lượng muốn bán',
            'province_id' => 'Tỉnh',
            'type_coffee' => 'Type Coffee',
            'price' => 'Gia Coffee',
            'location' => 'Location',
            'location_name' => 'Vị trí',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

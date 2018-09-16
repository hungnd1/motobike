<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "device_subscriber_asm".
 *
 * @property integer $id
 * @property integer $device_id
 * @property integer $subscriber_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class DeviceSubscriberAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_subscriber_asm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'subscriber_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'subscriber_id' => 'Subscriber ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

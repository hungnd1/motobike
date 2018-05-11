<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscriber_service_asm".
 *
 * @property integer $id
 * @property integer $service_id
 * @property integer $time_expired
 * @property integer $subscriber_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SubscriberServiceAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber_service_asm';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'time_expired', 'subscriber_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service ID',
            'time_expired' => 'Time Expired',
            'subscriber_id' => 'Subscriber ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

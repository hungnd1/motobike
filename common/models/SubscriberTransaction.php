<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscriber_transaction".
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property integer $type
 * @property integer $service_id
 * @property integer $transaction_time
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $balance
 * @property string $description
 * @property string $error_code
 * @property integer $subscriber_service_asm_id
 * @property integer $expired_time
 */
class SubscriberTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber_transaction';
    }

    const STATUS_SUCCESS = 10;
    const STATUS_FAIL = 0;

    const TYPE_REGISTER = 1;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_id', 'type', 'service_id', 'transaction_time', 'created_at', 'updated_at', 'status', 'balance', 'subscriber_service_asm_id', 'expired_time'], 'integer'],
            [['description'], 'string', 'max' => 500],
            [['error_code'], 'string', 'max' => 100],
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
            'type' => 'Type',
            'service_id' => 'Service ID',
            'transaction_time' => 'Transaction Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'balance' => 'Balance',
            'description' => 'Description',
            'error_code' => 'Error Code',
            'subscriber_service_asm_id' => 'Subscriber Service Asm ID',
            'expired_time' => 'Expired Time',
        ];
    }
}

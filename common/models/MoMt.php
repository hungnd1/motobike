<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mo_mt".
 *
 * @property integer $id
 * @property string $from_number
 * @property string $to_number
 * @property string $message_mo
 * @property integer $request_id
 * @property string $message_mt
 * @property integer $status_sync
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class MoMt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mo_mt';
    }

    CONST STATUS_ACTIVE = 0;
    CONST STATUS_INACTIVE = 1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_mo', 'message_mt'], 'string'],
            [['request_id', 'status_sync', 'status', 'created_at', 'updated_at'], 'integer'],
            [['from_number', 'to_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_number' => 'From Number',
            'to_number' => 'To Number',
            'message_mo' => 'Message Mo',
            'request_id' => 'Request ID',
            'message_mt' => 'Message Mt',
            'status_sync' => 'Status Sync',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscriber_group_subscriber_asm".
 *
 * @property integer $id
 * @property integer $subscriber_group_id
 * @property integer $subscriber_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SubscriberGroupSubscriberAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber_group_subscriber_asm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_group_id', 'subscriber_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber_group_id' => 'Subscriber Group ID',
            'subscriber_id' => 'Subscriber ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

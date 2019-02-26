<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscriber_dictionary".
 *
 * @property integer $id
 * @property integer $subscriber
 * @property string $image
 * @property string $content
 * @property integer $created_at
 * @property integer $group_id
 */
class SubscriberDictionary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber_dictionary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber', 'created_at', 'group_id'], 'integer'],
            [['content'], 'string'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber' => 'Subscriber',
            'image' => 'Image',
            'content' => 'Content',
            'created_at' => 'Created At',
            'group_id' => 'Group ID',
        ];
    }
}

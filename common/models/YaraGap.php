<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yara_gap".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $image
 * @property integer $order
 * @property integer $fruit_id
 */
class YaraGap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yara_gap';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['created_at', 'updated_at', 'status', 'order', 'fruit_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['short_description', 'image'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'short_description' => 'Short Description',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'image' => 'Image',
            'order' => 'Order',
            'fruit_id' => 'Fruit ID',
        ];
    }
}

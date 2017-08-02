<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_data".
 *
 * @property integer $id
 * @property string $latitude
 * @property string $longitude
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type_coffee
 */
class LogData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_data';
    }

    const CONTENT = 'content';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['created_at', 'updated_at','type_coffee'], 'integer'],
            [['latitude', 'longitude'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'latitude' => 'Kinh độ',
            'longitude' => 'Vĩ độ',
            'content' => 'Nội dung',
            'created_at' => 'Ngày tạo mới',
            'updated_at' => 'Ngày cập nhật',
            'type_coffee' => 'Loại coffee',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "feature".
 *
 * @property integer $id
 * @property string $display_name
 */
class Feature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'string', 'max' => 255],
            [['display_name'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Đặc điểm cây trồng',
        ];
    }
}

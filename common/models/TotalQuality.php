<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "total_quality".
 *
 * @property integer $id
 * @property double $min_total_quality
 * @property double $max_total_quality
 */
class TotalQuality extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'total_quality';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min_total_quality', 'max_total_quality'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'min_total_quality' => 'Tổng sản lượng nhỏ nhất',
            'max_total_quality' => 'Tổng sản lượng lớn nhất',
        ];
    }
}

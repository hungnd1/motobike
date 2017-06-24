<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sold".
 *
 * @property integer $id
 * @property double $min_sold
 * @property double $max_sold
 */
class Sold extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min_sold', 'max_sold'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'min_sold' => 'Sản lương đã bán nhỏ nhất',
            'max_sold' => 'Sản lượng đá bán lớn nhất',
        ];
    }
}

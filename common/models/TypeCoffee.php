<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_coffee".
 *
 * @property integer $id
 * @property string $name
 */
class TypeCoffee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_coffee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
        ];
    }
}

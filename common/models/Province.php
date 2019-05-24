<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property integer $id
 * @property string $province_name
 * @property string $province_code
 * @property string $province_name_sms
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_name','province_name_sms'], 'string', 'max' => 255],
            [['province_code'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_name' => 'Tên tỉnh',
            'province_code' => 'Mã tỉnh',
            'province_name_sms'=>'Ten khong dau'
        ];
    }
}

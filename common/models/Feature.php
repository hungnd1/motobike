<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "feature".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $display_name_en
 * @property integer $order
 * @property integer $status
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

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name','display_name_en'], 'string', 'max' => 255],
            [['display_name'], 'required'],
            [['order', 'status'], 'integer']
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
            'order' => 'Sắp xếp',
            'status' => 'Trạng thái'
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => \Yii::t('app', 'Kích hoạt'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Tạm dừng'),
        ];
        return $lst;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }
}

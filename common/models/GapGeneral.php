<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gap_general".
 *
 * @property integer $id
 * @property string $gap
 * @property string $title
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property float $temperature_max
 * @property float $temperature_min
 * @property float $evaporation
 * @property float $humidity
 */
class GapGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gap_general';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const GAP_GENERAL = 1;
    const GAP_DETAIL = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gap','title'], 'required'],
            [['gap','title'], 'string'],
            [['status', 'created_at', 'updated_at','type'], 'integer'],
            [['temperature_max','temperature_min','evaporation','humidity'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gap' => 'Nội dung',
            'status' => 'Trạng thái',
            'title' => 'Tiêu đề',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'temperature_max' => 'Nhiệt độ',
            'temperature_min' => 'Nhiệt độ nhỏ nhất',
            'evaporation' => 'Lượng mưa',
            'humidity' => 'Độ ẩm',
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

    public static function getListStatusNameByStatus($status){
        $lst = self::listStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }
}

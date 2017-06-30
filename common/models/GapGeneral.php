<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gap_general".
 *
 * @property integer $id
 * @property string $gap
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gap'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gap' => 'Gap',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

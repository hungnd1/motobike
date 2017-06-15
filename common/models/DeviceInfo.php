<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "device_info".
 *
 * @property integer $id
 * @property integer $device_type
 * @property string $device_uid
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class DeviceInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_info';
    }

    const TYPE_IOS = 1;
    const TYPE_ANDROID  = 2;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_type', 'device_uid'], 'required'],
            [['device_type', 'created_at', 'updated_at','status'], 'integer'],
            [['device_uid'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_type' => 'Loại thiết bị',
            'device_uid' => 'Uid thiết bị',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'status' => 'Trạng thái'
        ];
    }

    public static $device_type = [
        self::TYPE_IOS => "IOS",
        self::TYPE_ANDROID => "Android"
    ];
    public static function getListStatus(){
        return
            $credential_status = [
                self::STATUS_INACTIVE => Yii::t('app','Tạm dừng'),
                self::STATUS_ACTIVE => Yii::t('app','Hoạt động'),
            ];
    }

    public static function getListType(){
        return
            $credential_status = [
                self::TYPE_IOS => "IOS",
                self::TYPE_ANDROID => "Android",
            ];
    }

    public static function getListStatusFilter($type = 'all')
    {
        return ['all' => [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Khóa',
        ],
            'filter' => [
                self::STATUS_ACTIVE => 'Hoạt động',
                self::STATUS_INACTIVE => 'Khóa',
            ],
        ][$type];
    }

    public static function getListStatusNameByStatus($status){
        $lst = self::getListStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }

    public static function getListStatusNameByType($status){
        $lst = self::getListType();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }
}

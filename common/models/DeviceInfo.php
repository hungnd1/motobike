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
 * @property integer $mac
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_subscriber_id
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

    const TYPE_WEATHER = 1;
    const TYPE_PRICE = 2;
    const TYPE_QUESTION = 3;
    const TYPE_PETS = 4;


    const TARGET_TYPE_WEATHER = 'weather';
    const TARGET_TYPE_PROFILE = 'profile';
    const TARGET_TYPE_PEST = 'pest';
    const TARGET_TYPE_PRICE= 'price';
    const TARGET_TYPE_EXCHANGE = 'exchange';
    const TARGET_TYPE_GAPS = 'gaps';
    const TARGET_TYPE_CLIMATE = 'climate';
    const TARGET_TYPE_QUESTION = 'question';
    const TARGET_TYPE_GAP_ADVICE = 'gap';
    const TARGET_TYPE_PROBLEM = 'problems'; //su co bat thuong

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_type', 'device_uid'], 'required'],
            [['device_type', 'created_at', 'updated_at','status','last_subscriber_id'], 'integer'],
            [['device_uid','mac'], 'string', 'max' => 500],
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
            'status' => 'Trạng thái',
            'last_subscriber_id'=>'last_subscriber_id'
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

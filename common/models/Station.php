<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "station".
 *
 * @property integer $id
 * @property string $station_code
 * @property string $station_name
 * @property integer $province_id
 * @property integer $district_id
 * @property integer $status
 * @property integer $district_code
 * @property integer $com_code
 * @property string $url_weather
 * @property string $latitude
 * @property string $longtitude
 * @property string $district_name
 */
class Station extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'station';
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    const STATION_CODE = 'station_code';
    const STATION_NAME = 'station_name';
    const DISTRICT_CODE = 'district_code';
    const COM_CODE = 'com_code';
    const DISTRICT_NAME = 'district_name';
    const PROVINCE_ID = 'province_id';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_name','province_id','district_id','latitude','longtitude'],'required'],
            [['province_id','status','district_id','district_code','com_code'], 'integer'],
            [['station_name','district_name','station_code'], 'string', 'max' => 255],
            [['url_weather'], 'string', 'max' => 500],
            [['latitude', 'longtitude'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_code' => 'Mã xã',
            'station_name' => 'Tên xã',
            'province_id' => 'Tỉnh',
            'district_id' => 'Huyện',
            'url_weather' => 'Link thời tiết',
            'latitude' => 'Vĩ độ',
            'status' => 'Trạng thái',
            'longtitude' => 'Kinh độ',
        ];
    }

    public static function getListProvince()
    {

        $listArr = array();
        $lstPro = Province::find()->all();
        foreach ($lstPro as $item) {
            /** @var $item Province */
            $listArr[$item->id] = $item->province_name;
        }
        return $listArr;
    }

    public static function getProvinceDetail($province_id)
    {
        $province = Province::findOne(['id' => $province_id]);
        return $province->province_name;
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

    public static function getListStatus(){
        return
            $credential_status = [
                self::STATUS_ACTIVE => Yii::t('app','Hoạt động'),
                self::STATUS_INACTIVE => Yii::t('app','Tạm dừng'),
            ];
    }

    public static function getListStatusNameByStatus($status){
        $lst = self::getListStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }
}

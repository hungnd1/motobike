<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "weather_detail".
 *
 * @property integer $id
 * @property string $station_code
 * @property integer $precipitation
 * @property integer $tmax
 * @property integer $tmin
 * @property integer $wnddir
 * @property integer $wndspd
 * @property integer $station_id
 * @property integer $timestamp
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $clouddc
 * @property float $hprcp
 * @property float $hsun
 * @property integer $RFTMAX
 * @property integer $RFTMIN
 * @property integer $PROPRCP
 * @property string $wnddtxt
 * @property string $wtxt
 */
class WeatherDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weather_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['precipitation', 'tmax', 'tmin', 'wnddir', 'wndspd', 'station_id', 'timestamp', 'created_at', 'updated_at',
                'clouddc', 'RFTMAX', 'RFTMIN', 'PROPRCP'
            ], 'integer'],
            [['station_code'], 'string', 'max' => 45],
            [['hsun', 'hprcp', 'wnddtxt', 'wtxt'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_code' => 'Xã',
            'precipitation' => 'Lương mưa (mm)',
            'tmax' => 'Nhiệt độ cao nhất (⁰C)',
            'tmin' => 'Nhiệt đô thấp nhất (⁰C)',
            'wnddir' => 'Hướng gió',
            'wndspd' => 'Tốc độ gió',
            'station_id' => 'Station ID',
            'timestamp' => 'Timestamp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function convertWind($wind)
    {
        $wind = $wind / 3.6;
        $levelWind = '';
        if ($wind >= 0 && $wind <= 0.2) {
            $levelWind = 'Cấp 0';
        } elseif ($wind >= 0.3 && $wind <= 1.5) {
            $levelWind = 'Cấp 1';
        }elseif ($wind >= 1.6 && $wind <= 3.3) {
            $levelWind = 'Cấp 2';
        }elseif ($wind >= 3.4 && $wind <= 5.4) {
            $levelWind = 'Cấp 3';
        }elseif ($wind >= 5.5 && $wind <= 7.9) {
            $levelWind = 'Cấp 3';
        }elseif ($wind >= 8 && $wind <= 10.7) {
            $levelWind = 'Cấp 5';
        }elseif ($wind >= 10.8 && $wind <= 13.8) {
            $levelWind = 'Cấp 6';
        }elseif ($wind >= 13.9 && $wind <= 17.1) {
            $levelWind = 'Cấp 7';
        }elseif ($wind >= 17.2 && $wind <= 20.7) {
            $levelWind = 'Cấp 8';
        }elseif ($wind >= 20.8 && $wind <= 24.4) {
            $levelWind = 'Cấp 9';
        }elseif ($wind >= 24.5 && $wind <= 28.4) {
            $levelWind = 'Cấp 10';
        }elseif ($wind >= 28.5 && $wind <= 32.6) {
            $levelWind = 'Cấp 11';
        }elseif ($wind >= 32.7 && $wind <= 36.9) {
            $levelWind = 'Cấp 12';
        }else{
            $levelWind = 'Cấp 13';
        }
        return $levelWind;
    }
}

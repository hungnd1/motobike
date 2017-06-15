<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "weatherstationasm".
 *
 * @property integer $id
 * @property integer $temp
 * @property integer $humidity
 * @property integer $bleeding
 * @property integer $rainfall
 * @property integer $created_at
 * @property integer $station_id
 */
class Weatherstationasm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weatherstationasm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['temp', 'humidity', 'bleeding', 'rainfall', 'created_at', 'station_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'temp' => 'Temp',
            'humidity' => 'Humidity',
            'bleeding' => 'Bleeding',
            'rainfall' => 'Rainfall',
            'created_at' => 'Created At',
            'station_id' => 'Station ID',
        ];
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jul-17
 * Time: 2:04 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\models\WeatherDetail;
use Yii;
use yii\base\InvalidValueException;

class WeatherController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'get-weather-detail'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-weather-detail' => ['GET']
        ];
    }

    public function actionGetWeatherDetail()
    {
        $station_id = $this->getParameter('station_id', '');
        if (!$station_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Station ID ')]));
        }

        $current_time = time() + 7 * 60 * 60;
        $today = strtotime('today midnight') + 7 * 60 * 60;
        $tomorrow = strtotime('tomorrow') + 7 * 60 * 60;
        $week_ago = strtotime('today midnight') - 7 * 86400 + 7 * 60 * 60;
        $weather = null;
        $weatherCount = WeatherDetail::find()
            ->andWhere(['>=', 'timestamp', $today])
            ->andWhere(['<', 'timestamp', $tomorrow])
            ->andWhere(['station_id' => $station_id])
            ->orderBy(['timestamp' => SORT_ASC])->count();
        if ($weatherCount >= 2) {
            $weatherAll = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->andWhere(['station_id' => $station_id])
                ->orderBy(['timestamp' => SORT_DESC])->all();
            foreach ($weatherAll as $item) {
                /** @var $item WeatherDetail */
                if ($current_time > $item->timestamp) {
                    $weather = $item;
                }
            }
        } else {
            $weather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->andWhere(['station_id' => $station_id])
                ->orderBy(['timestamp' => SORT_ASC])->one();
        }
        if (!$weather) {
            $weather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->andWhere(['station_id' => $station_id])
                ->orderBy(['timestamp' => SORT_DESC])
                ->limit(1)
                ->one();
        }
        if ($weather) {
            $listWeather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $current_time])
                ->andWhere(['<>', 'id', $weather->id])
                ->andWhere(['station_id' => $station_id])
                ->orderBy(['timestamp' => SORT_ASC])
                ->all();
        } else {
            $listWeather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $current_time])
                ->andWhere(['station_id' => $station_id])
                ->orderBy(['timestamp' => SORT_ASC])
                ->all();
        }

        $weekWeatherAgo = WeatherDetail::find()
            ->andWhere(['>=', 'timestamp', $week_ago])
            ->andWhere(['<=', 'timestamp', $today])
            ->andWhere(['station_id' => $station_id])
            ->orderBy(['timestamp' => SORT_ASC])
            ->all();

        return [
            'items' => $weather,
            'events' => $listWeather,
            'weather_week_ago' => $weekWeatherAgo
        ];
    }
}
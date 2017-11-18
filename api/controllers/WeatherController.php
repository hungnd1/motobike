<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jul-17
 * Time: 2:04 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
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
        $arr = [];
        $current_time = time();
        $today = strtotime('today midnight');
        $tomorrow = strtotime('tomorrow');
        $week_ago = strtotime('today midnight') - 4 * 86400 + 7 * 60 * 60;
        $week_feature = strtotime('today midnight') + 4 * 86400 + 7 * 60 * 60;
        $weather = null;
        $weatherCount = WeatherDetail::find()
            ->andWhere(['>=', 'timestamp', $today])
            ->andWhere(['<', 'timestamp', $tomorrow])
            ->andWhere(['station_id' => $station_id])
            ->andWhere('tmax is not null')
            ->andWhere('tmin is not null')
            ->orderBy(['timestamp' => SORT_ASC])->count();
        if ($weatherCount >= 2) {
            $weatherAll = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->andWhere(['station_id' => $station_id])
                ->andWhere('tmax is not null')
                ->andWhere('tmin is not null')
                ->orderBy(['timestamp' => SORT_ASC])->all();
            foreach ($weatherAll as $item) {
                /** @var $item WeatherDetail */
                if ($current_time >= $item->timestamp) {
                    $weather = $item;
                }
            }
        } else {
            $weather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->andWhere('tmax is not null')
                ->andWhere('tmin is not null')
                ->andWhere(['station_id' => $station_id])
                ->orderBy(['timestamp' => SORT_ASC])->one();
        }
        if (!$weather) {
            $weather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->andWhere(['station_id' => $station_id])
                ->andWhere('tmax is not null')
                ->andWhere('tmin is not null')
                ->orderBy(['timestamp' => SORT_DESC])
                ->limit(1)
                ->one();
        }
        if ($weather) {
            $listWeather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $current_time])
                ->andWhere(['<>', 'id', $weather->id])
                ->andWhere(['station_id' => $station_id])
                ->andWhere('tmax is not null')
                ->andWhere('tmin is not null')
                ->orderBy(['timestamp' => SORT_ASC])
                ->all();
            $time = 0;
            foreach ($listWeather as $item) {
                /** @var $item WeatherDetail */
                if ($time == 0) {
                    $time = $item->timestamp;
                    array_push($arr, $item);
                } else {
                    $date = date('Y-m-d', $time);
                    $end = new \DateTime($date);
                    $end->setTime(23, 59, 59);
                    $toTimeDefault = $end->getTimestamp();
                    if ($item->timestamp >= $toTimeDefault) {
                        $time = $item->timestamp;
                        array_push($arr, $item);
                    }
                }
            }
        } else {
            $listWeather = WeatherDetail::find()
                ->andWhere(['>=', 'timestamp', $current_time])
                ->andWhere(['station_id' => $station_id])
                ->andWhere('tmax is not null')
                ->andWhere('tmin is not null')
                ->orderBy(['timestamp' => SORT_ASC])
                ->all();
            $time = 0;
            foreach ($listWeather as $item) {
                /** @var $item WeatherDetail */
                $date = date('Y-m-d', $item->timestamp);
                $end = new \DateTime($date);
                $end->setTime(23, 59, 59);
                $toTimeDefault = $end->getTimestamp();
                if ($time == 0) {
                    $time = $item->timestamp;
                    array_push($arr, $item);
                } else {
                    if ($item->timestamp >= $toTimeDefault) {
                        array_push($arr, $item);
                    }
                }
            }
        }

        $weekWeatherAgo = WeatherDetail::find()
            ->andWhere(['>=', 'timestamp', $week_ago])
            ->andWhere(['<=', 'timestamp', $week_feature])
            ->andWhere(['station_id' => $station_id])
            ->andWhere('tmax is not null')
            ->andWhere('tmin is not null')
            ->orderBy(['timestamp' => SORT_ASC])->all();
        $temperature = 0;
        $precipitation = 0;
//        foreach ($weekWeatherAgo->all() as $item) {
//            /** @var $item WeatherDetail */
//            $temperature += ($item->tmax + $item->tmin) / 2;
//            $precipitation += $item->precipitation;
//        }
//        if ($weekWeatherAgo->count() > 0) {
//            $temperature = $temperature / $weekWeatherAgo->count();
//            $precipitation = $precipitation / $weekWeatherAgo->count();
//        }

        return [
            'items' => $weather,
//            'temperature' => $temperature,
//            'precipitation' => $precipitation,
            'events' => $arr,
            'weather_week_ago' => $weekWeatherAgo
        ];
    }
}
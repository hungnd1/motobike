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
use common\models\Station;
use common\models\Subscriber;
use common\models\SubscriberActivity;
use common\models\SubscriberServiceAsm;
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
            'get-weather-detail-except',
//            'get-weather-detail',
//            'get-detail'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-weather-detail' => ['GET'],
            'get-weather-detail-except' => ['GET'],
        ];
    }

    public function actionGetWeatherDetail()
    {
        UserHelpers::manualLogin();

        $subscriber = Yii::$app->user->identity;
        /** @var  $subscriber Subscriber */
        $station_id = $this->getParameter('station_id', '');
        if (!$station_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Station ID ')]));

        }

        $description = 'Nguoi dung vao thoi tiet ' . $station_id;
        $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_WEATHER, $description);

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
        $subscriber->weather_detail_id = $weather->id;
        $subscriber->save(false);
        return [
            'items' => $weather,
//            'temperature' => $temperature,
//            'precipitation' => $precipitation,
            'events' => $arr,
            'weather_week_ago' => $weekWeatherAgo
        ];
    }

    public function actionGetWeatherDetailExcept()
    {
//        UserHelpers::manualLogin();

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

    public function actionGetDetail()
    {
        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        /** @var  $subscriber Subscriber */

        $today = strtotime('today midnight');
        $tomorrow = strtotime('tomorrow');

//        $subscriber = Yii::$app->user->identity;
        /** @var  $subscriber Subscriber */
        /** @var  $subscriberServiceAsm  SubscriberServiceAsm */
        $sql = "select station_code, (((acos(sin((" . Yii::$app->request->headers->get(static::HEADER_LATITUDE) . "*pi()/180)) * 
            sin((`latitude`*pi()/180))+cos((" . Yii::$app->request->headers->get(static::HEADER_LATITUDE) . "*pi()/180)) *
            cos((`latitude`*pi()/180)) * cos(((" . Yii::$app->request->headers->get(static::HEADER_LONGITUDE) . "- `longtitude`)*pi()/180))))*180/pi())*60*1.1515) 
            as distance
            FROM station where latitude is not null and longtitude is not null  order by distance asc limit 1";
        $connect = Yii::$app->getDb();
        $command = $connect->createCommand($sql);
        $result = $command->queryAll();
        $stationCode = $result[0]['station_code'];
        if (!$stationCode) {
            if ($subscriber->weather_detail_id) {
                /** @var  $weather  WeatherDetail */
                $weather = WeatherDetail::find()->andWhere(['id' => $subscriber->weather_detail_id])->one();
                if ($weather) {
                    $stationCode = $weather->station_code;
                }
            }
        }
        /** @var  $weatherDetail WeatherDetail */
        $weatherDetail = WeatherDetail::find()
            ->andWhere(['station_code' => $stationCode])
            ->andWhere(['>=', 'timestamp', $today])
            ->andWhere(['<', 'timestamp', $tomorrow])
            ->one();

        $weatherDetail = WeatherDetail::findOne(['id' => $weatherDetail->id]);
        return $weatherDetail;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 06-Jul-17
 * Time: 10:18 AM
 */

namespace console\controllers;


use api\models\Subscriber;
use common\helpers\CUtils;
use common\helpers\FileUtils;
use common\models\DeviceInfo;
use common\models\PriceCoffee;
use common\models\Station;
use common\models\WeatherDetail;
use Yii;
use yii\base\Controller;

class PriceController extends Controller
{


    public function actionRun()
    {
        $this->migrateLatLong();
//        date_default_timezone_set("Asia/Bangkok");
//        $arr_price_name = ['dABA', 'dABC', 'dABE', 'dABF', 'dACA', 'dACC', 'dACE', 'dACF', 'dRBF', 'dRCA', 'dRCC', 'dRCE', 'dRCF', 'dRBA', 'dRBC', 'dRBE'];
//        $api_organisation = Yii::$app->params['GreenCoffee'];
//        $api_price_detail = Yii::$app->params['price_detail'];
//        $arr_organisation = $this->callCurl($api_organisation);
//        $page = ceil($arr_organisation['count'] / 10);
//        for ($i = 1; $i <= $page; $i++) {
//            $api_organisation_ = $api_organisation . '&page=' . $i;
//            PriceController::infoLog('URL ' . $api_organisation_);
//            $arr_organisation = $this->callCurl($api_organisation_);
//            for ($j = 0; $j < sizeof($arr_organisation['results']); $j++) {
//                $name_ = $arr_organisation['results'][$j]['name'];
//                if (in_array($name_, $arr_price_name)) {
//                    $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
//                    PriceController::infoLog('URL ' . $api_price_detail_);
//                    $arr_detail = $this->callCurl($api_price_detail_);
//                    $id = $arr_detail['results'][0]['id'];
//                    $name = $arr_organisation['results'][$j]['location']['name'];
//                    PriceController::infoLog('*******START  TO CHUC  ' . $name);
//                    $lastTime = $arr_detail['results'][0]['last_value_timestamp'] / 1000;
//                    $last_value = $arr_detail['results'][0]['last_value'];
//                    $organisation_name = $arr_detail['results'][0]['name'];
//                    $event_arr = $arr_detail['results']['0']['events'];
//                    $check = PriceCoffee::find()
//                        ->andWhere(['organisation_name' => $organisation_name, 'province_id' => $name])
//                        ->andWhere('coffee_old_id is null')->all();
//                    foreach ($check as $price) {
//                        /** @var $price PriceCoffee */
//                        $price->coffee_old_id = $id;
//                        $price->save();
//                    }
//                    $checkOldId = PriceCoffee::find()->andWhere(['coffee_old_id' => $id])
//                        ->andWhere(['<', 'created_at', strtotime('today midnight') + 7 * 60 * 60])->one();
//                    if (!$checkOldId) {
//                        $this->infoLog1('Chua co gia tri nao ca');
//                        if ($last_value) {
//                            if (sizeof($event_arr) >= 1) {
//                                for ($k = 0; $k < sizeof($event_arr); $k++) {
//                                    $price = new PriceCoffee();
//                                    $price->province_id = $name;
//                                    $price->price_average = $event_arr[$k]['value'];
//                                    $price->last_time_value = $event_arr[$k]['timestamp'] / 1000;
//                                    $price->unit = PriceCoffee::UNIT_VND;
//                                    $price->created_at = $event_arr[$k]['timestamp'] / 1000;
//                                    $price->updated_at = $event_arr[$k]['timestamp'] / 1000;
//                                    $price->coffee_old_id = $id;
//                                    $price->organisation_name = $organisation_name;
//                                    $price->save();
//                                }
//                            }
//                        }
//                    } else
//                        if ($last_value) {
//                            if (sizeof($event_arr) >= 1) {
//                                for ($k = 0; $k < sizeof($event_arr); $k++) {
//                                    if ($k == sizeof($event_arr) - 1) {
//                                        $this->infoLog1('Gia tri cuoi cung');
//                                        if ($last_value != $event_arr[sizeof($event_arr) - 1]['timestamp'] / 1000) {
//                                            $priceOld = PriceCoffee::find()
//                                                ->andWhere(['province_id' => $name])
//                                                ->andWhere(['created_at' => $last_value])
//                                                ->andWhere(['organisation_name' => $organisation_name])
//                                                ->andWhere(['coffee_old_id' => $id])->one();
//                                            if (!$priceOld) {
//                                                $price = new PriceCoffee();
//                                                $price->province_id = $name;
//                                                $price->price_average = $last_value;
//                                                $price->last_time_value = $lastTime;
//                                                $price->unit = PriceCoffee::UNIT_VND;
//                                                $price->created_at = $lastTime;
//                                                $price->updated_at = $lastTime;
//                                                $price->coffee_old_id = $id;
//                                                $price->organisation_name = $organisation_name;
//                                                $price->save();
//                                            }
//                                        }
//                                    }else{
//                                        $priceOld = PriceCoffee::find()
//                                            ->andWhere(['province_id' => $name])
//                                            ->andWhere(['created_at' => $event_arr[$k]['timestamp'] / 1000])
//                                            ->andWhere(['organisation_name' => $organisation_name])
//                                            ->andWhere(['coffee_old_id' => $id])->one();
//
//                                        /** @var $priceOld PriceCoffee */
//                                        if ($priceOld) {
//                                            $this->infoLog1('Da ton tai gia tri');
//                                            if ($priceOld->price_average != $event_arr[$k]['value']) {
//                                                $priceOld->price_average = $event_arr[$k]['value'];
//                                                $priceOld->updated_at = time();
//                                                $priceOld->save(false);
//                                            }
//                                        } else {
//                                            $this->infoLog1('Chua ton tai gia tri');
//                                            $price = new PriceCoffee();
//                                            $price->province_id = $name;
//                                            $price->price_average = $event_arr[$k]['value'];
//                                            $price->last_time_value = $event_arr[$k]['timestamp'] / 1000;
//                                            $price->unit = PriceCoffee::UNIT_VND;
//                                            $price->created_at = $event_arr[$k]['timestamp'] / 1000;
//                                            $price->updated_at = $event_arr[$k]['timestamp'] / 1000;
//                                            $price->coffee_old_id = $id;
//                                            $price->organisation_name = $organisation_name;
//                                            $price->save();
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                }
//            }
//        }
    }

    public static function errorLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/error.log'), $txt);
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info.log'), $txt);
    }

    public static function infoLog1($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info1.log'), $txt);
    }

    public static function infoLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info.log'), $txt);
    }

    public static function infoLogWeather($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info_weather.log'), $txt);
    }

    public static function infoLogPrice($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info_price_lizard.log'), $txt);
    }

    public function actionDemo()
    {
        PriceController::infoLog("aaaaaa");
    }

    public function actionFirstTime()
    {

        $api_organisation = Yii::$app->params['GreenCoffee'];
        $api_price_detail = Yii::$app->params['price_detail'];
        $arr_price_name = ['dABA', 'dABC', 'dABE', 'dABF', 'dACA', 'dACC', 'dACE', 'dACF', 'dRBF', 'dRCA', 'dRCC', 'dRCE', 'dRCF', 'dRBA', 'dRBC', 'dRBE'];

        $arr_organisation = $this->callCurl($api_organisation);
        $page = ceil($arr_organisation['count'] / 10);
        for ($i = 1; $i <= $page; $i++) {
            $api_organisation_ = $api_organisation . '&page=' . $i;
            PriceController::infoLog('URL ' . $api_organisation_);
            $arr_organisation = $this->callCurl($api_organisation_);
            for ($j = 0; $j < sizeof($arr_organisation['results']); $j++) {
                $name = $arr_organisation['results'][$j]['name'];
                if (in_array($name, $arr_price_name)) {
                    $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
                    PriceController::infoLog('URL ' . $api_price_detail_);
                    $arr_detail = $this->callCurl($api_price_detail_);
                    $event_arr = $arr_detail['results']['0']['events'];
                    $first_time_value = $arr_detail['results']['0']['first_value_timestamp'] / 1000;
                    $last_time_value = $arr_detail['results']['0']['last_value_timestamp'] / 1000;
                    $id = $arr_detail['results'][0]['id'];
                    $name = $arr_organisation['results'][$j]['location']['name'];
                    $last_value = $arr_detail['results'][0]['last_value'];
                    $organisation_name = $arr_detail['results'][0]['name'];
                    $checkOldId = PriceCoffee::find()->andWhere(['coffee_old_id' => $id])
                        ->andWhere(['<', 'created_at', -strtotime('today midnight') + 7 * 60 * 60])->one();
                    if (!$checkOldId) {
                        if ($last_value) {
                            if (sizeof($event_arr) >= 1) {
                                for ($k = 0; $k < sizeof($event_arr); $k++) {
                                    if ($last_time_value == $event_arr[$k]['timestamp'] / 1000) {
                                        $day_next = floor((strtotime('today midnight') + 7 * 60 * 60 - $last_time_value) / 86400);
                                        if ($day_next >= 1) {
                                            for ($t = 0; $t <= $day_next; $t++) {
                                                $price = new PriceCoffee();
                                                $price->province_id = $name;
                                                $price->price_average = $event_arr[$k]['value'];
                                                $price->unit = PriceCoffee::UNIT_VND;
                                                $price->created_at = $event_arr[$k]['timestamp'] / 1000 + 86400 * $t;
                                                $price->updated_at = $event_arr[$k]['timestamp'] / 1000 + 86400 * $t;
                                                $price->organisation_name = $organisation_name;
                                                $price->last_time_value = $event_arr[$k]['timestamp'] / 1000;
                                                $price->coffee_old_id = $id;
                                                $price->save(false);
                                            }
                                        }
                                    } else {
                                        $price = new PriceCoffee();
                                        $price->province_id = $name;
                                        $price->price_average = $event_arr[$k]['value'];
                                        $price->unit = PriceCoffee::UNIT_VND;
                                        $price->created_at = $event_arr[$k]['timestamp'] / 1000;
                                        $price->updated_at = $event_arr[$k]['timestamp'] / 1000;
                                        $price->organisation_name = $organisation_name;
                                        $price->last_time_value = $event_arr[$k]['timestamp'] / 1000;
                                        $price->coffee_old_id = $id;
                                        $price->save(false);
                                        if ($k < sizeof($event_arr) - 1) {
                                            $day_next = floor(($event_arr[$k + 1]['timestamp'] / 1000 - $event_arr[$k]['timestamp'] / 1000) / 86400);
                                            if ($day_next > 1) {
                                                for ($t = 1; $t < $day_next; $t++) {
                                                    $price = new PriceCoffee();
                                                    $price->province_id = $name;
                                                    $price->price_average = $event_arr[$k]['value'];
                                                    $price->unit = PriceCoffee::UNIT_VND;
                                                    $price->created_at = $event_arr[$k]['timestamp'] / 1000 + 86400 * $t;
                                                    $price->updated_at = $event_arr[$k]['timestamp'] / 1000 + 86400 * $t;
                                                    $price->organisation_name = $organisation_name;
                                                    $price->last_time_value = $event_arr[$k]['timestamp'] / 1000;
                                                    $price->coffee_old_id = $id;
                                                    $price->save(false);
                                                }
                                            }
                                        } else {
                                            $day_next = floor((strtotime('today midnight') + 7 * 60 * 60 - $event_arr[$k]['timestamp'] / 1000) / 86400);
                                            if ($day_next > 1) {
                                                for ($t = 1; $t < $day_next; $t++) {
                                                    $price = new PriceCoffee();
                                                    $price->province_id = $name;
                                                    $price->price_average = $event_arr[$k]['value'];
                                                    $price->unit = PriceCoffee::UNIT_VND;
                                                    $price->created_at = $event_arr[$k]['timestamp'] / 1000 + 86400 * $t;
                                                    $price->updated_at = $event_arr[$k]['timestamp'] / 1000 + 86400 * $t;
                                                    $price->organisation_name = $organisation_name;
                                                    $price->last_time_value = $event_arr[$k]['timestamp'] / 1000;
                                                    $price->coffee_old_id = $id;
                                                    $price->save(false);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $day_next = floor((strtotime('today midnight') + 7 * 60 * 60 - $last_time_value) / 86400);
                                if ($day_next >= 1) {
                                    for ($r = 0; $r <= $day_next; $r++) {
                                        $price = new PriceCoffee();
                                        $price->province_id = $name;
                                        $price->price_average = $last_value;
                                        $price->unit = PriceCoffee::UNIT_VND;
                                        $price->created_at = $last_time_value + $r * 86400;
                                        $price->updated_at = $last_time_value + $r * 86400;
                                        $price->organisation_name = $organisation_name;
                                        $price->last_time_value = $last_time_value;
                                        $price->coffee_old_id = $id;
                                        $price->save(false);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function callCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8', 'username: duc.dam', 'password:Kopainfo2017'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ch_result = curl_exec($ch);
        curl_close($ch);
        $arr_detail = json_decode($ch_result, true);
        return $arr_detail;
    }

    private function migrateLatLong()
    {

        $station = Station::findAll(['status' => Station::STATUS_ACTIVE]);
        $url = "https://greencoffee.lizard.net/api/v3/locations/?format=json&code=";
        foreach ($station as $item) {
            /** @var $item Station */
            if (!$item->longtitude || !$item->latitude) {
                $arr_detail = $this->callCurl($url . $item->station_code);
                $value = $arr_detail['results'];
                if ($value) {
                    $long = $arr_detail['results'][0]['geometry']['coordinates'][0];
                    $lat = $arr_detail['results'][0]['geometry']['coordinates'][1];
                    $item->latitude = $lat;
                    $item->longtitude = $long;
                    $item->save(false);
                }
            }
        }
    }

    public function actionMigrateLatLong()
    {

        $station = Station::findAll(['status' => Station::STATUS_ACTIVE]);
        $url = "https://greencoffee.lizard.net/api/v3/locations/?format=json&code=";
        foreach ($station as $item) {
            /** @var $item Station */
            $arr_detail = $this->callCurl($url . $item->station_code);
            $value = $arr_detail['results'];
            if ($value) {
                if (!$item->longtitude || !$item->latitude) {
                    $long = $arr_detail['results'][0]['geometry']['coordinates'][0];
                    $lat = $arr_detail['results'][0]['geometry']['coordinates'][1];
                    $item->latitude = $lat;
                    $item->longtitude = $long;
                    $item->save(false);
                }
            }
        }
    }

    public function actionRunWeather()
    {

        $this->infoLogWeather("**** TIME START" . time());
        $listStation = Station::find()
            ->andWhere(['status' => Station::STATUS_ACTIVE])
//            ->andWhere(['station_code'=>'67_665_24728'])
//            ->limit(1)
            ->all();
        $today = strtotime('today midnight') + 7 * 60 * 60;
        $tomorrow = strtotime('tomorrow') + 7 * 60 * 60;
        $arr_price_name = ['dABA', 'dABC', 'dABE', 'dABF', 'dACA', 'dACC', 'dACE', 'dACF', 'dRBF', 'dRCA', 'dRCC', 'dRCE', 'dRCF', 'dRBA', 'dRBC', 'dRBE'];
        foreach ($listStation as $station) {
            /** @var $station Station */
            $url = "https://greencoffee.lizard.net/api/v3/timeseries/?end=1501545600000&format=json&page_size=17&start=1498802400000&search=" . $station->station_code;
            $this->infoLogWeather("****START WEATHER URL " . $url . "******* /n");
            $this->infoLogWeather("****STATION " . $station->id . "*******");
//            $url = "https://greencoffee.lizard.net/api/v3/timeseries/?end=1601545600000&format=json&search=67_663_24667&start=1498802400000";
            $station_result = $this->callCurl($url);
            $tmax = 0;
            $tmin = 0;
            $precipitation = 0;
            $wind_spd = 0;
            $wind_dir = 0;
            $cloudc = 0;
            $hprcp = 0;
            $hsun = 0;
            $rftmax = 0;
            $rftmin = 0;
            $timestamp = 0;
            if ($station_result['results'] && sizeof($station_result['results']) >= 1) {
                for ($i = 0; $i < sizeof($station_result['results']); $i++) {
                    if (!in_array($station_result['results'][$i]['code'], $arr_price_name)) {
                        // them lan luot luong mua, nhiet do max-min, huong gio, toc do gio vao theo ngay va neu time khong trung nhau thi them moi ngay nho hon
                        $array_event = $station_result['results'][$i]['events'];
                        $code = $station_result['results'][$i]['code'];
                        $last_value = $station_result['results'][$i]['last_value'];
                        $end = $station_result['results'][$i]['end'] / 1000;
                        if ($end) {
                            $checkStart = WeatherDetail::findOne(['station_code' => $station->station_code, 'timestamp' => $end]);
                            if (!$checkStart) {
                                $timestamp = $end;
                                if ($code == 'PRCP') {
                                    $precipitation = $last_value;
                                } elseif ($code == 'TMAX') {
                                    $tmax = $last_value;
                                } elseif ($code == 'TMIN') {
                                    $tmin = $last_value;
                                } elseif ($code == 'WNDDIR') {
                                    $wind_dir = $last_value;
                                } elseif ($code == 'WNDSPD') {
                                    $wind_spd = $last_value;
                                } elseif ($code == 'CLOUDC') {
                                    $cloudc = $last_value;
                                } elseif ($code == 'HPRCP') {
                                    $hprcp = $last_value;
                                } elseif ($code == 'HSUN') {
                                    $hsun = $last_value;
                                } elseif ($code == 'RFTMAX') {
                                    $rftmax = $last_value;
                                } elseif ($code == 'RFTMIN') {
                                    $rftmin = $last_value;
                                }
                            }
                            if (sizeof($array_event) > 0) {
                                for ($j = 0; $j < sizeof($array_event); $j++) {
                                    $checkDetail = WeatherDetail::findOne(['station_code' => $station->station_code, 'timestamp' => $array_event[$j]['timestamp'] / 1000]);
                                    // check timestamp va station_code da ton tai chua
                                    if (!$checkDetail) {
                                        if ($code == 'PRCP') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->precipitation = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'TMAX') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->tmax = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'TMIN') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->tmin = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'WNDDIR') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->wnddir = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'WNDSPD') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->wndspd = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'CLOUDC') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->clouddc = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'HPRCP') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->hprcp = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'HSUN') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->hsun = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'RFTMAX') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->RFTMAX = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        } elseif ($code == 'RFTMIN') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->RFTMIN = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
//                                            $weather_detail->save();
                                        }


                                    } else {
                                        //chay lan dau thi comment if lai
                                        if ($code == 'PRCP') {
                                            if ($checkDetail->precipitation != $array_event[$j]['max'] || !$checkDetail->precipitation) {
                                                $checkDetail->precipitation = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'TMAX') {
                                            if ($checkDetail->tmax != $array_event[$j]['max'] || !$checkDetail->tmax) {
                                                $checkDetail->tmax = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'TMIN') {
                                            if ($checkDetail->tmin != $array_event[$j]['max'] || !$checkDetail->tmin) {
                                                $checkDetail->tmin = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'WNDDIR') {
                                            if ($checkDetail->wnddir != $array_event[$j]['max'] || !$checkDetail->wnddir) {
                                                $checkDetail->wnddir = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'WNDSPD') {
                                            if ($checkDetail->wndspd != $array_event[$j]['max'] || !$checkDetail->wndspd) {
                                                $checkDetail->wndspd = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'CLOUDC') {
                                            if ($checkDetail->clouddc != $array_event[$j]['max'] || !$checkDetail->clouddc) {
                                                $checkDetail->clouddc = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'HPRCP') {
                                            if ($checkDetail->hprcp != $array_event[$j]['max'] || !$checkDetail->hprcp) {
                                                $checkDetail->hprcp = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'HSUN') {
                                            if ($checkDetail->hsun != $array_event[$j]['max'] || !$checkDetail->hsun) {
                                                $checkDetail->hsun = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'RFTMAX') {
                                            if ($checkDetail->RFTMAX != $array_event[$j]['max'] || !$checkDetail->RFTMAX) {
                                                $checkDetail->RFTMAX = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        } elseif ($code == 'RFTMIN') {
                                            if ($checkDetail->RFTMIN != $array_event[$j]['max'] || !$checkDetail->RFTMIN) {
                                                $checkDetail->RFTMIN = $array_event[$j]['max'];
//                                                $checkDetail->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($timestamp) {
                $weather_detail = new WeatherDetail();
                $weather_detail->precipitation = $precipitation;
                $weather_detail->tmin = $tmin;
                $weather_detail->tmax = $tmax;
                $weather_detail->wnddir = $wind_dir;
                $weather_detail->wndspd = $wind_spd;
                $weather_detail->clouddc = $cloudc;
                $weather_detail->hsun = $hsun;
                $weather_detail->hprcp = $hprcp;
                $weather_detail->RFTMAX = $rftmax;
                $weather_detail->RFTMIN = $rftmin;
                $weather_detail->timestamp = $timestamp;
                $weather_detail->created_at = time();
                $weather_detail->updated_at = time();
                $weather_detail->station_code = $station->station_code;
                $weather_detail->station_id = $station->id;
            }
        }
        $this->infoLogWeather("**** TIME END" . time());
    }

    // ket noi api lay gia
    public function actionRunPrice()
    {

        $this->infoLogPrice("**** TIME START PRICE " . date('d/m/Y', time()));
        $listStation = Station::find()
            ->andWhere(['status' => Station::STATUS_ACTIVE])
//            ->andWhere(['station_code'=>'67_665_24728'])
//            ->limit(1)
            ->all();
        $arr_price_name = ['dABA', 'dABC', 'dABE', 'dABF', 'dACA', 'dACC', 'dACE', 'dACF', 'dRBF', 'dRCA', 'dRCC', 'dRCE', 'dRCF', 'dRBA', 'dRBC', 'dRBE'];
        foreach ($listStation as $station) {
            /** @var $station Station */
            $url = "https://greencoffee.lizard.net/api/v3/timeseries/?end=1601545600000&format=json&page_size=20&start=1498802400000&search=" . $station->station_code;
            $station_result = $this->callCurl($url);
            if ($station_result['results'] && sizeof($station_result['results']) >= 1) {
                for ($i = 0; $i < sizeof($station_result['results']); $i++) {
                    if (in_array($station_result['results'][$i]['code'], $arr_price_name)) {
                        $id = $station_result['results'][$i]['id'];
                        $code = $station_result['results'][$i]['code'];
                        $last_value = $station_result['results'][$i]['last_value'];
                        $end = $station_result['results'][$i]['end'] / 1000;
                        if ($end) {
                            $checkStart = PriceCoffee::findOne(['province_id' => $station->station_code, 'last_time_value' => $end, 'organisation_name' => $code]);
                            if (!$checkStart) {
                                $priceCoffee = new PriceCoffee();
                                $priceCoffee->province_id = $station->station_code;
                                $priceCoffee->price_average = $last_value;
                                $priceCoffee->unit = 1;
                                $priceCoffee->created_at = $end;
                                $priceCoffee->updated_at = $end;
                                $priceCoffee->last_time_value = $end;
                                $priceCoffee->coffee_old_id = $id;
                                $priceCoffee->organisation_name = $code;
                                $priceCoffee->save(false);
                                $this->infoLogPrice("**** Save new price " . $station->station_code . " " . $code . " price la " . $last_value);
                            } else {
                                if ($checkStart->price_average != $last_value) {
                                    $checkStart->price_average = $last_value;
                                    $checkStart->last_time_value = $end;
                                    $checkStart->updated_at = $end;
                                    $checkStart->save(false);
                                    $this->infoLogPrice("**** Save old price " . $station->station_code . " " . $code . " price la " . $last_value);

                                }
                            }
                        }
                    }
                }
            }
        }
        $this->infoLogWeather("**** TIME END" . time());
    }

    public function actionNotifyPrice()
    {
        $device_token = DeviceInfo::find()
            ->innerJoin('device_subscriber_asm', 'device_subscriber_asm.device_id = device_info.id')
            ->all();
        $clickAction = Yii::$app->params['action_android'];
        foreach ($device_token as $token) {
            /** @var $token DeviceInfo */
            CUtils::sendNotify($token->device_uid, "Bấm vào để xem chi tiết giá cà phê hôm nay", "Giá cả", $clickAction, DeviceInfo::TYPE_PRICE, 1, DeviceInfo::TARGET_TYPE_PRICE);
        }
    }

    public function actionNotifyWeather()
    {
        $clickAction = Yii::$app->params['action_android'];
        $lstUser = Subscriber::find()->andWhere(['status' => Subscriber::STATUS_ACTIVE])->all();
        foreach ($lstUser as $subscriber) {
            /** @var $subscriber Subscriber */
            /** @var  $device_token DeviceInfo */
            $device_token = DeviceInfo::find()
                ->innerJoin('device_subscriber_asm', 'device_subscriber_asm.device_id = device_info.id')
                ->andWhere(['device_subscriber_asm.subscriber_id' => $subscriber->id])
                ->one();
            if ($subscriber->weather_detail_id && $device_token) {
                /** @var  $station Station */
                $station = Station::findOne(['station_code' => $subscriber->weather_detail_id]);
                CUtils::sendNotify($device_token->device_uid, "Bấm vào để xem thời tiết ngày hôm nay", "Thời tiết", $clickAction, DeviceInfo::TYPE_WEATHER, $station->id, DeviceInfo::TARGET_TYPE_WEATHER);
            }
        }
    }
}
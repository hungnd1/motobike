<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 06-Jul-17
 * Time: 10:18 AM
 */

namespace console\controllers;


use common\helpers\FileUtils;
use common\models\PriceCoffee;
use common\models\Station;
use common\models\WeatherDetail;
use Yii;
use yii\base\Controller;

class PriceController extends Controller
{


    public function actionRun()
    {
        date_default_timezone_set("Asia/Bangkok");
        $today = strtotime('today midnight') + 7 * 60 * 60;
        $arr_price_name = ['dABA','dABC','dABE','dABF','dACA','dACC','dACE','dACF','dRBF','dRCA','dRCC','dRCE','dRCF', 'dRBA', 'dRBC', 'dRBE'];
        $tomorrow = strtotime('tomorrow') + 7 * 60 * 60;
        $api_organisation = Yii::$app->params['GreenCoffee'];
        $api_price_detail = Yii::$app->params['price_detail'];
        $arr_organisation = $this->callCurl($api_organisation);
        $page = ceil($arr_organisation['count'] / 10);
        for ($i = 1; $i <= $page; $i++) {
            $api_organisation_ = $api_organisation . '&page=' . $i;
            PriceController::infoLog('URL ' . $api_organisation_);
            $arr_organisation = $this->callCurl($api_organisation_);
            for ($j = 0; $j < sizeof($arr_organisation['results']); $j++) {
                $name_ = $arr_organisation['results'][$j]['name'];
                if (in_array($name_, $arr_price_name)) {
                    $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
                    PriceController::infoLog('URL ' . $api_price_detail_);
                    $arr_detail = $this->callCurl($api_price_detail_);
                    $id = $arr_detail['results'][0]['id'];
                    $name = $arr_organisation['results'][$j]['location']['name'];
                    PriceController::infoLog('*******START  TO CHUC  ' . $name);

                    $price_average = $arr_detail['results'][0]['last_value'];
                    $last_time_value = $arr_detail['results'][0]['last_value_timestamp'] / 1000;
                    $last_value = $arr_detail['results'][0]['last_value'];
                    $organisation_name = $arr_detail['results'][0]['name'];

                    if ($last_value) {
                        $date = date('d/m/Y', time());
                        $to_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
                        $from_time = $to_time - 86400 * 29;
                        $checkPriceExpired = PriceCoffee::find()
                            ->distinct('price_average')
                            ->andWhere(['>=', 'created_at', $from_time + 7 * 60 * 60])
                            ->andWhere(['<=', 'created_at', $to_time + 7 * 60 * 60])
                            ->andWhere(['coffee_old_id' => $id])
                            ->orderBy(['created_at' => SORT_DESC]);
                        if ($checkPriceExpired->count() >= 2) {
                            $priceOld = PriceCoffee::find()
                                ->andWhere(['coffee_old_id' => $id])
                                ->orderBy(['id' => SORT_DESC])->one();
                            /** @var $priceOld PriceCoffee */
                            if ($priceOld) {
                                if (time() + 7 * 60 * 60 - $priceOld->created_at < 86400 && $last_time_value > $priceOld->last_time_value && $last_time_value < $tomorrow) {
                                    $priceOld->price_average = $price_average;
                                    $priceOld->last_time_value = $last_time_value;
                                    $priceOld->updated_at = time();
                                    $priceOld->save(false);
                                    PriceController::infoLog(' Thoi gian cuoi cung lon hon thoi gian ghi trong database ');
                                } elseif (time() + 7 * 60 * 60 - $priceOld->created_at >= 86400) {
                                    $day_next = floor((time() + 7 * 60 * 60 - $priceOld->created_at) / 86400);
                                    for ($t = 1; $t <= $day_next; $t++) {
                                        $price = new PriceCoffee();
                                        $price->province_id = $name;
                                        $price->price_average = $price_average;
                                        $price->unit = PriceCoffee::UNIT_VND;
                                        $price->created_at = $priceOld->created_at + 86400 * $t;
                                        $price->updated_at = $priceOld->created_at + 86400 * $t;
                                        $price->organisation_name = $organisation_name;
                                        $price->last_time_value = $last_time_value;
                                        $price->coffee_old_id = $id;
                                        $price->save(false);
                                        PriceController::infoLog(' Thoi gian cuoi cung  bang thoi gian  hien tai ');
                                    }
                                }
                            } elseif ($last_time_value < $tomorrow) {
                                $price = new PriceCoffee();
                                $price->province_id = $name;
                                $price->price_average = $price_average;
                                $price->unit = PriceCoffee::UNIT_VND;
                                $price->created_at = $today;
                                $price->updated_at = $today;
                                $price->organisation_name = $organisation_name;
                                $price->last_time_value = $last_time_value;
                                $price->coffee_old_id = $id;
                                $price->save(false);
                                PriceController::infoLog(' Thoi gian cuoi cung nho hon  thoi gian ngay mai  ');
                            }
                        }
                    }
                }
            }
        }
    }

    public static function errorLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/error.log'), $txt);
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info.log'), $txt);
    }

    public static function infoLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info.log'), $txt);
    }

    public static function infoLogWeather($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info_weather.log'), $txt);
    }

    public function actionDemo()
    {
        PriceController::infoLog("aaaaaa");
    }

    public function actionFirstTime()
    {

        $api_organisation = Yii::$app->params['GreenCoffee'];
        $api_price_detail = Yii::$app->params['price_detail'];
        $arr_price_name = ['dABA','dABC','dABE','dABF','dACA','dACC','dACE','dACF','dRBF','dRCA','dRCC','dRCE','dRCF', 'dRBA', 'dRBC', 'dRBE'];

        $arr_organisation = $this->callCurl($api_organisation);
        $page = ceil($arr_organisation['count'] / 10);
        for ($i = 1; $i <= $page; $i++) {
            $api_organisation_ = $api_organisation . '&page=' . $i;
            PriceController::infoLog('URL ' . $api_organisation_);

            $arr_organisation = $this->callCurl($api_organisation_);
            for ($j = 0; $j < sizeof($arr_organisation['results']); $j++) {
                $name = $arr_organisation['results'][$j]['location']['name'];

                if (in_array($name, $arr_price_name)) {
                    $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
                    PriceController::infoLog('URL ' . $api_price_detail_);
                    $arr_detail = $this->callCurl($api_price_detail_);
                    $event_arr = $arr_detail['results']['0']['events'];
                    $first_time_value = $arr_detail['results']['0']['first_value_timestamp'] / 1000;
                    $last_time_value = $arr_detail['results']['0']['last_value_timestamp'] / 1000;
                    $id = $arr_detail['results'][0]['id'];

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

        $listStation = Station::find()
            ->andWhere(['status' => Station::STATUS_ACTIVE])
            ->andWhere('latitude is not null')
//            ->limit(1)
            ->all();
        $today = strtotime('today midnight') + 7 * 60 * 60;
        $arr_price_name = ['dRCC', 'dRBA', 'dRBC', 'dRBE'];
        foreach ($listStation as $station) {
            /** @var $station Station */
            $url = "https://greencoffee.lizard.net/api/v3/timeseries/?end=1601545600000&format=json&start=1498802400000&search=" . $station->station_code;
            $this->infoLogWeather("****START WEATHER URL " . $url . "******* /n");
            $this->infoLogWeather("****STATION " . $station->id . "*******");
//            $url = "https://greencoffee.lizard.net/api/v3/timeseries/?end=1601545600000&format=json&search=67_663_24667&start=1498802400000";
            $station_result = $this->callCurl($url);
            $tmax = 0;
            $tmin = 0;
            $precipitation = 0;
            $wind_spd = 0;
            $wind_dir = 0;
            $timestamp = 0;
            if ($station_result['results'] && sizeof($station_result['results']) >= 5) {
                for ($i = 0; $i < sizeof($station_result['results']); $i++) {
                    if (!in_array($station_result['results'][$i]['code'], $arr_price_name)) {
                        // them lan luot luong mua, nhiet do max-min, huong gio, toc do gio vao theo ngay va neu time khong trung nhau thi them moi ngay nho hon
                        $array_event = $station_result['results'][$i]['events'];
                        $code = $station_result['results'][$i]['code'];
                        $last_value = $station_result['results'][$i]['last_value'];
                        $end = $station_result['results'][$i]['end'] / 1000 - 6 * 60 * 60;
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
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000 - 6 * 60 * 60;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
                                            $weather_detail->save();
                                        } elseif ($code == 'TMAX') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->tmax = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000 - 6 * 60 * 60;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
                                            $weather_detail->save();
                                        } elseif ($code == 'TMIN') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->tmin = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000 - 6 * 60 * 60;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
                                            $weather_detail->save();
                                        } elseif ($code == 'WNDDIR') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->wnddir = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000 - 6 * 60 * 60;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
                                            $weather_detail->save();
                                        } elseif ($code == 'WNDSPD') {
                                            $weather_detail = new WeatherDetail();
                                            $weather_detail->wndspd = $array_event[$j]['max'];
                                            $weather_detail->timestamp = $array_event[$j]['timestamp'] / 1000 - 6 * 60 * 60;
                                            $weather_detail->created_at = time();
                                            $weather_detail->updated_at = time();
                                            $weather_detail->station_id = $station->id;
                                            $weather_detail->station_code = $station->station_code;
                                            $weather_detail->save();
                                        }
                                    } else {
                                        //chay lan dau thi comment if lai

                                        if ($checkDetail->precipitation != $array_event[$j]['max'] ||
                                            $checkDetail->tmax != $array_event[$j]['max'] ||
                                            $checkDetail->tmin != $array_event[$j]['max'] ||
                                            $checkDetail->wndspd != $array_event[$j]['max'] ||
                                            $checkDetail->wnddir != $array_event[$j]['max']
                                            || $array_event[$j]['timestamp'] / 1000 > $today || !$checkDetail->precipitation
                                            || !$checkDetail->tmax || !$checkDetail->tmin || !$checkDetail->wnddir || !$checkDetail->wndspd
                                        ) {
                                            if ($code == 'PRCP') {
                                                $checkDetail->precipitation = $array_event[$j]['max'];
                                                $checkDetail->save();
                                            } elseif ($code == 'TMAX') {
                                                $checkDetail->tmax = $array_event[$j]['max'];
                                                $checkDetail->save();
                                            } elseif ($code == 'TMIN') {
                                                $checkDetail->tmin = $array_event[$j]['max'];
                                                $checkDetail->save();
                                            } elseif ($code == 'WNDDIR') {
                                                $checkDetail->wnddir = $array_event[$j]['max'];
                                                $checkDetail->save();
                                            } elseif ($code == 'WNDSPD') {
                                                $checkDetail->wndspd = $array_event[$j]['max'];
                                                $checkDetail->save();
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
                $weather_detail->timestamp = $timestamp - 6 * 60 * 60;
                $weather_detail->created_at = time();
                $weather_detail->updated_at = time();
                $weather_detail->station_code = $station->station_code;
                $weather_detail->station_id = $station->id;
            }
        }
    }
}
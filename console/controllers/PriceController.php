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
use Yii;
use yii\base\Controller;

class PriceController extends Controller
{


    public function actionRun()
    {
        date_default_timezone_set("Asia/Bangkok");
        $today = strtotime('today midnight') + 7 * 60 * 60;
        $arr_price_name = ['dRCC', 'dRBA', 'dRBC', 'dRBE'];
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
                $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
                PriceController::infoLog('URL ' . $api_price_detail_);
                $arr_detail = $this->callCurl($api_price_detail_);
                $id = $arr_detail['results'][0]['id'];
                $name = $arr_detail['results'][0]['location']['name'];

                PriceController::infoLog('*******START  TO CHUC  ' . $name);

                $price_average = $arr_detail['results'][0]['last_value'];
                $last_time_value = $arr_detail['results'][0]['last_value_timestamp'] / 1000;
                $last_value = $arr_detail['results'][0]['last_value'];
                $organisation_name = $arr_detail['results'][0]['name'];
                if (in_array($name, $arr_price_name) || in_array($organisation_name, $arr_price_name)) {
                    if ($last_value) {
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

    public static function errorLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/error.log'), $txt);
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info.log'), $txt);
    }

    public static function infoLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info.log'), $txt);
    }

    public function actionDemo()
    {
        PriceController::infoLog("aaaaaa");
    }

    public function actionFirstTime()
    {

        $api_organisation = Yii::$app->params['GreenCoffee'];
        $api_price_detail = Yii::$app->params['price_detail'];
        $arr_price_name = ['dRCC', 'dRBA', 'dRBC', 'dRBE'];

        $arr_organisation = $this->callCurl($api_organisation);
        $page = ceil($arr_organisation['count'] / 10);
        for ($i = 1; $i <= $page; $i++) {
            $api_organisation_ = $api_organisation . '&page=' . $i;
            PriceController::infoLog('URL ' . $api_organisation_);

            $arr_organisation = $this->callCurl($api_organisation_);
            for ($j = 0; $j < sizeof($arr_organisation['results']); $j++) {
                $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
//                $api_price_detail_ = 'https://greencoffee.lizard.net/api/v2/timeseries/?end=1690171661710&min_points=320&start=1488521600001&format=json&uuid=95594060-0e7c-4d9e-902a-b900a0342c1e';
                PriceController::infoLog('URL ' . $api_price_detail_);
                $arr_detail = $this->callCurl($api_price_detail_);
                $event_arr = $arr_detail['results']['0']['events'];
                $first_time_value = $arr_detail['results']['0']['first_value_timestamp'] / 1000;
                $last_time_value = $arr_detail['results']['0']['last_value_timestamp'] / 1000;
                $id = $arr_detail['results'][0]['id'];
                $name = $arr_detail['results'][0]['location']['name'];
                $last_value = $arr_detail['results'][0]['last_value'];
                $organisation_name = $arr_detail['results'][0]['name'];
                $checkOldId = PriceCoffee::find()->andWhere(['coffee_old_id' => $id])
                    ->andWhere(['<', 'created_at', -strtotime('today midnight') + 7 * 60 * 60])->one();
                if (in_array($name, $arr_price_name) || in_array($organisation_name, $arr_price_name)) {
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
}
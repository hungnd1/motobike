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
        $today = strtotime('today midnight');
        $tomorrow = strtotime('tomorrow');
        $api_organisation = Yii::$app->params['GreenCoffee'];
        $api_price_detail = Yii::$app->params['price_detail'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_organisation);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8', 'username: duc.dam', 'password:Kopainfo2017'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ch_result = curl_exec($ch);
        curl_close($ch);
        $arr_organisation = json_decode($ch_result, true);
        $page = ceil($arr_organisation['count'] / 10);
        for ($i = 1; $i <= $page; $i++) {
            $api_organisation_ = $api_organisation . '&page=' . $i;
            PriceController::infoLog('URL ' . $api_organisation_);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_organisation_);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8', 'username: duc.dam', 'password:Kopainfo2017'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ch_result = curl_exec($ch);
            curl_close($ch);
            $arr_organisation = json_decode($ch_result, true);
            for ($j = 0; $j < sizeof($arr_organisation['results']); $j++) {
                $api_price_detail_ = $api_price_detail . $arr_organisation['results'][$j]['uuid'];
                PriceController::infoLog('URL ' . $api_price_detail_);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_price_detail_);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8', 'username: duc.dam', 'password:Kopainfo2017'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $ch_result = curl_exec($ch);
                curl_close($ch);
                $arr_detail = json_decode($ch_result, true);
                $id = $arr_detail['results'][0]['id'];
                $name = $arr_detail['results'][0]['location']['name'];

                PriceController::infoLog('*******START  TO CHUC  ' . $name);

                $price_average = $arr_detail['results'][0]['last_value'];
                $last_time_value = $arr_detail['results'][0]['last_value_timestamp'] / 1000;
                $last_value = $arr_detail['results'][0]['last_value'];
                $organisation_name  = $arr_detail['results'][0]['name'];
                if ($last_value) {
                    if ($last_time_value >= $tomorrow) {
                        $price = new PriceCoffee();
                        $price->province_id = $name;
                        $price->price_average = $price_average;
                        $price->unit = PriceCoffee::UNIT_VND;
                        $price->created_at = $tomorrow;
                        $price->updated_at = $tomorrow;
                        $price->last_time_value = $last_time_value;
                        $price->coffee_old_id = $id;
                        $price->organisation_name = $organisation_name;
                        $price->save(false);
                        PriceController::infoLog(' Thoi gian cuoi cung lon hon ngay mai ');
                    } else {
                        $priceOld = PriceCoffee::find()
                            ->andWhere(['coffee_old_id' => $id])
                            ->orderBy(['id' => SORT_DESC])->one();
                        /** @var $priceOld PriceCoffee */
                        if ($priceOld) {
                            if ($last_time_value > $priceOld->last_time_value && $last_time_value < $tomorrow) {
                                $priceOld->price_average = $price_average;
                                $priceOld->last_time_value = $last_time_value;
                                $priceOld->updated_at = time();
                                $priceOld->save(false);
                                PriceController::infoLog(' Thoi gian cuoi cung lon hon thoi gian ghi trong database ');
                            }elseif(time() - $priceOld->created_at >= 86400){
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
                                PriceController::infoLog(' Thoi gian cuoi cung  bang thoi gian  hien tai ');
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

    public function actionDemo(){
        PriceController::infoLog("aaaaaa");
    }

}
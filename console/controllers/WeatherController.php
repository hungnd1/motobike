<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 07-Oct-17
 * Time: 8:26 PM
 */

namespace console\controllers;


use api\models\Station;
use api\models\WeatherDetail;
use common\helpers\FileUtils;
use common\models\PriceCoffee;
use PHPExcel_IOFactory;
use Yii;
use yii\console\Controller;

class WeatherController extends Controller
{
    public function actionGetFile()
    {
        // define some variables
        $local_file = Yii::$app->params['folder'] . 'backend/web/AccuWeather_Central_Highlands_Vietnam.csv';
        $server_file = '/incoming/09_NIAPP/Weather/AccuWeather_Central_Highlands_Vietnam.csv';
        $ftp_server = "ftp.nelen-schuurmans.nl";
        $ftp_user_name = "greencoffee";
        $ftp_user_pass = "nice cup of green coffee";

        $conn_id = ftp_connect($ftp_server);
//
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
//
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            $this->infoLogWeather("Successfully written to $local_file\n");
            ftp_close($conn_id);
            $this->infoLogWeather("Start update weather");
            $objPHPExcel = PHPExcel_IOFactory::load($local_file);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            if (sizeof($sheetData) > 0) {
                foreach ($sheetData as $row) {
                    $rowA = strtotime(str_replace('Z', '', str_replace('T', ' ', trim($row['A']))));
                    $hour = 0;
                    $minute = 0;
                    $second = 0;
                    if (strpos(trim($row['A']), '+') !== false) {
                        $time = explode('+', trim($row['A']))[1];
                        $hour = isset(explode(':', $time)[0]) ? explode(':', $time)[0] : 0;
                        $minute = isset(explode(':', $time)[1]) ? explode(':', $time)[1] : 0;
                        $second = isset(explode(':', $time)[2]) ? explode(':', $time)[2] : 0;
                    }
                    $rowA += $hour * 3600 + $minute * 60 + $second;
                    $weatherDetail = WeatherDetail::find()
                        ->andWhere(['timestamp' => $rowA])
//                            ->andWhere(['station_code'=>'67_663_24664'])
                        ->andWhere(['station_code' => trim($row['D'])])
                        ->one();
                    /** @var $weatherDetail WeatherDetail */
                    if ($weatherDetail) {
                        if (trim($row['B']) == 'PRCP') {
                            $weatherDetail->precipitation = trim($row['C']);
                        } elseif (trim($row['B']) == 'TMAX') {
                            $weatherDetail->tmax = trim($row['C']);
                        } elseif (trim($row['B']) == 'TMIN') {
                            $weatherDetail->tmin = trim($row['C']);
                        } elseif (trim($row['B']) == 'WNDDIR') {
                            $weatherDetail->wnddir = trim($row['C']);
                        } elseif (trim($row['B']) == 'WNDSPD') {
                            $weatherDetail->wndspd = trim($row['C']);
                        } elseif (trim($row['B']) == 'CLOUDC') {
                            $weatherDetail->clouddc = trim($row['C']);
                        } elseif (trim($row['B']) == 'HPRCP') {
                            $weatherDetail->hprcp = trim($row['C']);
                        } elseif (trim($row['B']) == 'HSUN') {
                            $weatherDetail->hsun = trim($row['C']);
                        } elseif (trim($row['B']) == 'RFTMAX') {
                            $weatherDetail->RFTMAX = trim($row['C']);
                        } elseif (trim($row['B']) == 'RFTMIN') {
                            $weatherDetail->RFTMIN = trim($row['C']);
                        } elseif (trim($row['B']) == 'PROPRCP') {
                            $weatherDetail->PROPRCP = trim($row['C']);
                        }
                        $weatherDetail->save(false);
                        $this->infoLogWeather(trim($row['B']) . " , " . trim($row['C']) . " , " . trim($row['C']));
                    } else {
                        $weather = new WeatherDetail();
                        $weather->station_code = trim($row['D']);
                        $weather->station_id = Station::findOne(['station_code' => trim($row['D'])]) ? Station::findOne(['station_code' => trim($row['D'])])->id : 0;
                        $weather->timestamp = $rowA;
                        $weather->created_at = $rowA;
                        $weather->updated_at = $rowA;
                        if (trim($row['B']) == 'PRCP') {
                            $weather->precipitation = trim($row['C']);
                        } elseif (trim($row['B']) == 'TMAX') {
                            $weather->tmax = trim($row['C']);
                        } elseif (trim($row['B']) == 'TMIN') {
                            $weather->tmin = trim($row['C']);
                        } elseif (trim($row['B']) == 'WNDDIR') {
                            $weather->wnddir = trim($row['C']);
                        } elseif (trim($row['B']) == 'WNDSPD') {
                            $weather->wndspd = trim($row['C']);
                        } elseif (trim($row['B']) == 'CLOUDC') {
                            $weather->clouddc = trim($row['C']);
                        } elseif (trim($row['B']) == 'HPRCP') {
                            $weather->hprcp = trim($row['C']);
                        } elseif (trim($row['B']) == 'HSUN') {
                            $weather->hsun = trim($row['C']);
                        } elseif (trim($row['B']) == 'RFTMAX') {
                            $weather->RFTMAX = trim($row['C']);
                        } elseif (trim($row['B']) == 'RFTMIN') {
                            $weather->RFTMIN = trim($row['C']);
                        } elseif (trim($row['B']) == 'PROPRCP') {
                            $weather->PROPRCP = trim($row['C']);
                        }
                        $weather->save(false);
                    }
//                    }
                }
            }

        } else {
            $this->infoLogWeather("Have problem");
        }
    }

    public static function infoLogWeather($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/infoweather.log'), $txt);
    }


    public function actionDownloadPrice()
    {
        // define some variables
        $local_file = Yii::$app->params['folder'] . 'backend/web/Coffeeprices_Central_Highlands_Vietnam.csv';
        $server_file = '/incoming/10_IPSARD/Coffeeprices_Central_Highlands_Vietnam.csv';
        $ftp_server = "ftp.nelen-schuurmans.nl";
        $ftp_user_name = "greencoffee";
        $ftp_user_pass = "nice cup of green coffee";

        $conn_id = ftp_connect($ftp_server);

        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            $this->infoLogWeather("Successfully written to $local_file\n");
            ftp_close($conn_id);
            $this->infoLogWeather("Start update price");
            $objPHPExcel = PHPExcel_IOFactory::load($local_file);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            if (sizeof($sheetData) > 0) {
                foreach ($sheetData as $row) {
                    $rowA = strtotime(str_replace('Z', '', str_replace('T', ' ', trim($row['A'])))) + 7 * 3600;
                    $coffee_old = PriceCoffee::findOne(['organisation_name' => trim($row['B']), 'province_id' => trim($row['D']), 'created_at' => $rowA]);
                    if (!$coffee_old) {
                        $price = new PriceCoffee();
                        $price->province_id = trim($row['D']);
                        $price->price_average = trim($row['C']);
                        $price->unit = PriceCoffee::UNIT_VND;
                        $price->created_at = $rowA;
                        $price->updated_at = $rowA;
                        $price->last_time_value = $rowA;
                        $coffee_old_id = PriceCoffee::findOne(['organisation_name' => $row['B'], 'province_id' => $row['D']]);
                        if ($coffee_old_id) {
                            $price->coffee_old_id = $coffee_old_id->coffee_old_id;
                        }
                        $price->organisation_name = trim($row['B']);
                        $price->save();
                    } else {
                        $coffee_old->price_average = trim($row['C']);
                        $coffee_old->save(false);
                    }
                }
            }

        } else {
            $this->infoLogWeather("Have problem price");
        }
    }
}
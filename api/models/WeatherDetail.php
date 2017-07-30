<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jul-17
 * Time: 5:18 PM
 */

namespace api\models;


use api\helpers\Common;
use common\models\Province;
use common\models\Station;

class WeatherDetail extends \common\models\WeatherDetail
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['image'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            $message = Common::precipitation($model->precipitation,$model->tmax);
            return $message['image'];
        };
        $fields['station_name'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return Station::findOne($model->station_id)->station_name;
        };

        $fields['wnddir'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return Common::windir($model->wnddir);
        };

        $fields['wndspd_km_h'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return floor($model->wndspd * 1000 / 60) . ' Km/h';
        };
        $fields['content'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            $message = Common::precipitation($model->precipitation,$model->tmax);
            return $message['message'];
        };
        $fields['t_average'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return floor(($model->tmax + $model->tmin) / 2). ' ⁰C';
        };
        $fields['province_name'] =  function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return Province::findOne(Station::findOne($model->station_id)->province_id)->province_name;
        };

        $fields['precipitation_unit'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return 'mm';
        };


        return $fields;
    }
}
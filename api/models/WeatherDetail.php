<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jul-17
 * Time: 5:18 PM
 */

namespace api\models;


use api\helpers\Common;
use api\helpers\UserHelpers;
use common\models\Feedback;
use common\models\Province;
use common\models\Station;
use Yii;

class WeatherDetail extends \common\models\WeatherDetail
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['image'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            $message = Common::precipitation($model->wtxt);
            return $message;
        };
        $fields['station_name'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return Station::findOne($model->station_id)->station_name;
        };

        $fields['wnddir'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return Common::windir($model->wnddtxt);
        };

        $fields['wndspd_km_h'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return $model->wndspd. ' Km/h' . ' (' . WeatherDetail::convertWind($model->wndspd) . ')';
        };
        $fields['content'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            $txt = explode('_',$model->wtxt);
            if(isset($txt['0'])){
                return $txt['0'];
            }
            return $model->wtxt;
        };
        $fields['t_average'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return floor(($model->tmax + $model->tmin) / 2) . ' ⁰C';
        };
        $fields['province_name'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return Province::findOne(Station::findOne($model->station_id)->province_id)->province_name;
        };

        $fields['precipitation_unit'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            return 'mm';
        };
        $fields['precipitation_average'] = function ($model) {
            /* @var $model \common\models\WeatherDetail */
            if ($model->precipitation > 2) {
                return ($model->precipitation - 2) . ' - ' . ($model->precipitation + 2);
            } elseif ($model->precipitation == 2) {
                return ($model->precipitation - 1) . ' - ' . ($model->precipitation + 2);
            }
            return $model->precipitation;
        };
        $fields['is_feedback'] = function ($model) {

            if(UserHelpers::manualLogin()){
                /** @var  $subscriber Subscriber */
                $subscriber = Yii::$app->user->identity;
                $feedback = Feedback::find()->andWhere(['user_id' => $subscriber->id])->orderBy(['created_at' => SORT_DESC])->one();
                if ($feedback) {
                    if ($feedback->created_at < time() - 12 * 60 * 60) {
                        return true;
                    }
                    return false;
                }
            }
            return false;
        };


        return $fields;
    }
}
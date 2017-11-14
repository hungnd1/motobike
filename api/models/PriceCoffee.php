<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 06-Jul-17
 * Time: 9:05 PM
 */

namespace api\models;


use common\models\Station;

class PriceCoffee extends \common\models\PriceCoffee
{
    public function fields()
    {
        $fields = parent::fields();
//        unset($fields['province_id']);
//        unset($fields['last_time_value']);

        $fields['province_name'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
            if ($model->organisation_name == 'dRCL') {
                return 'London';
            } elseif ($model->organisation_name == 'dACN') {
                return 'New york';
            }
            $province_name = Station::findOne(['station_code' => $model->province_id]);
            if ($province_name) {
                return $province_name->station_name;
            }
            return $model->province_id;
        };
        $fields['price_average'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
//            if ($model->organisation_name == 'dRCL') {
//                return ceil($model->price_average * 22675 / 1000);
//            }elseif($model->organisation_name == 'dACN'){
//                return ceil($model->price_average)
//            }
            return $model->price_average;
        };

        $fields['unit'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
            if ($model->organisation_name == 'dACN') {
                return 'USD/tấn';
            }elseif($model->organisation_name == 'dRCL'){
                return 'cent/lb';
            }
            return $model->getListStatusNameByUnit($model->unit);
        };
        $fields['type_coffee'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
            return $model->getPriceCode($model->organisation_name);
        };

        $fields['exchange'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */

            $pricePre = PriceCoffee::find()
                ->andWhere(['price_coffee.province_id' => $model->province_id])
                ->andWhere(['organisation_name' => $model->organisation_name])
                ->andWhere(['<','id',$model->id])
                ->orderBy(['id'=>SORT_DESC])
                ->limit(1)
                ->one();

            /** @var $pricePre PriceCoffee */
            if($model->price_average >= $pricePre->price_average){
                return '+'.round($model->price_average - $pricePre->price_average,2);
            }
            return '-'.round($pricePre->price_average - $model->price_average,2) ;
        };

        return $fields;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 06-Jul-17
 * Time: 9:05 PM
 */

namespace api\models;


class PriceCoffee extends \common\models\PriceCoffee
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['province_id']);
        unset($fields['last_time_value']);

        $fields['province_name'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
            return $model->province_id;
        };
        $fields['price_average'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
            return \common\helpers\CUtils::formatPrice($model->price_average);
        };

        $fields['unit'] = function ($model) {
            /* @var $model \common\models\PriceCoffee */
            return $model->getListStatusNameByUnit($model->unit);
        };

        return $fields;
    }
}
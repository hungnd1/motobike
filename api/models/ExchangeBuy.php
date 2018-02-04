<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 4:59 PM
 */

namespace api\models;


use common\models\Province;
use common\models\TotalQuality;
use common\models\TypeCoffee;

class ExchangeBuy extends \common\models\ExchangeBuy
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['type_coffee_id']);
        unset($fields['total_quantity']);
        unset($fields['price_buy']);

        $fields['coffee'] = function ($model) {
            /* @var $model \common\models\ExchangeBuy */
            $coffee = TypeCoffee::findOne($model->type_coffee_id);
            if($coffee){
                return $coffee->name;
            }
            return '';
        };

        $fields['total_quantity'] = function ($model) {
            /* @var $model \common\models\ExchangeBuy */
            return $model->total_quantity;
        };
        $fields['subscriber_name'] = function ($model) {
            /* @var $model \common\models\ExchangeBuy */
            $subscriber = Subscriber::findOne($model->subscriber_id);
            if($subscriber){
                return $subscriber->username;
            }
            return '';
        };
        $fields['price'] = function ($model) {
            /* @var $model \common\models\ExchangeBuy */
            return $model->price_buy;
        };
        $fields['full_name'] = function ($model) {
            /* @var $model \common\models\Exchange */
            $subscriber = Subscriber::findOne($model->subscriber_id);
            if($subscriber){
                return $subscriber->full_name ? $subscriber->full_name : 'Chưa cập nhật';
            }
            return '';
        };

        $fields['province'] = function ($model) {
            /* @var $model \common\models\Exchange */
            $province = Province::findOne($model->province_id);
            if($province){
                return $province->province_name ? $province->province_name : 'Chưa cập nhật';
            }
            return '';
        };


        return $fields;
    }
}
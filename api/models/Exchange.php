<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 29-Jun-17
 * Time: 10:16 PM
 */

namespace api\models;


use common\models\Province;
use common\models\Sold;
use common\models\Subscriber;
use common\models\TotalQuality;
use common\models\TypeCoffee;

class Exchange extends \common\models\Exchange
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['type_coffee']);

        $fields['coffee'] = function ($model) {
            /* @var $model \common\models\Exchange */
            $coffee = TypeCoffee::findOne($model->type_coffee);
            if($coffee){
                return $coffee->name;
            }
            return '';
        };

        $fields['total_quantity'] = function ($model) {
            /* @var $model \common\models\Exchange */
           return $model->total_quantity;
        };
        $fields['subscriber_name'] = function ($model) {
            /* @var $model \common\models\Exchange */
            $subscriber = Subscriber::findOne($model->subscriber_id);
            if($subscriber){
                return $subscriber->username;
            }
            return '';
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
<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 4:59 PM
 */

namespace api\models;


use common\models\TotalQuality;
use common\models\TypeCoffee;

class ExchangeBuy extends \common\models\ExchangeBuy
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['type_coffee_id']);
        unset($fields['total_quantity']);

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
            $quantity = TotalQuality::findOne($model->total_quantity);
            if($quantity){
                return $quantity->min_total_quality.'-'.$quantity->max_total_quality.' tấn';
            }
            return '';
        };
        $fields['subscriber_name'] = function ($model) {
            /* @var $model \common\models\Exchange */
            $subscriber = Subscriber::findOne($model->subscriber_id);
            if($subscriber){
                return $subscriber->username;
            }
            return '';
        };


        return $fields;
    }
}
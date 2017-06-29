<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 29-Jun-17
 * Time: 10:16 PM
 */

namespace api\models;


use common\models\Sold;
use common\models\TotalQuality;
use common\models\TypeCoffee;

class Exchange extends \common\models\Exchange
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['sold_id']);
        unset($fields['type_coffee']);
        unset($fields['total_quality_id']);

        $fields['sold'] = function ($model) {
            /* @var $model \common\models\Exchange */
            $sold = Sold::findOne($model->sold_id);
            if($sold){
                return $sold->min_sold.'-'.$sold->max_sold.' tấn';
            }
            return '';
        };

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
            $quantity = TotalQuality::findOne($model->total_quality_id);
            if($quantity){
                return $quantity->min_total_quality.'-'.$quantity->max_total_quality.' tấn';
            }
            return '';
        };


        return $fields;
    }

}
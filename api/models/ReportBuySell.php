<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/16/2018
 * Time: 11:33 PM
 */

namespace api\models;


use common\models\Province;
use common\models\TypeCoffee;

class ReportBuySell extends  \common\models\ReportBuySell
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['province_name'] = function ($model) {
            /** @var $model \common\models\ReportBuySell */
            return Province::findOne($model->province_id)->province_name;
        };
        $fields['coffee_name'] = function ($model){
            /** @var $model \common\models\ReportBuySell */
            return TypeCoffee::findOne($model->type_coffee)->name;
        };

        return $fields;
    }
}
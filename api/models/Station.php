<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 19-Jun-17
 * Time: 11:36 PM
 */

namespace api\models;


use common\models\Province;

class Station extends \common\models\Station
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['province_name'] = function ($model) {
            /* @var $model \common\models\Station */
           $province_name = Province::findOne(['id'=>$model->province_id])->province_name;
            return $province_name;
        };


        return $fields;
    }
}
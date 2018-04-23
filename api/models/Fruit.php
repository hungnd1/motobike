<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/23/2018
 * Time: 9:21 PM
 */

namespace api\models;


class Fruit extends \common\models\Fruit
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['content']);
        $fields['image'] = function ($model) {
            /* @var $model \common\models\Fruit */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };

        return $fields;
    }
}
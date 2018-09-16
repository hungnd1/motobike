<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/23/2018
 * Time: 9:21 PM
 */

namespace api\models;


class Group extends \common\models\Group
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['image'] = function ($model) {
            /* @var $model \common\models\Group */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };

        return $fields;
    }
}
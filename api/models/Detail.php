<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/23/2018
 * Time: 9:21 PM
 */

namespace api\models;


class Detail extends \common\models\Detail
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['image'] = function ($model) {
            /* @var $model \common\models\Detail */
            if ($model->image) {
                return $model->getImageLink();
            }
            return '';
        };

        return $fields;
    }
}
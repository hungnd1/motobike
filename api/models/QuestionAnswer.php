<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 11:29 AM
 */

namespace api\models;


class QuestionAnswer extends \common\models\QuestionAnswer
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['image'] = function ($model) {
            /* @var $model \common\models\QuestionAnswer */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };

        return $fields;
    }
}
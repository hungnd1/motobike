<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/23/2018
 * Time: 9:21 PM
 */

namespace api\models;


class Question extends \common\models\Question
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['question'] = function ($model) {
            /* @var $model \common\models\Question */
            if(\Yii::$app->language == 'en'){
                return $model->question_en;
            }
            return $model->question;
        };

        return $fields;
    }
}
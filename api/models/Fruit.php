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
        $fields['name'] = function ($model) {
            /* @var $model \common\models\Fruit */
            if(\Yii::$app->language == 'en'){
                return $model->name_en;
            }
            return $model->name;
        };

        return $fields;
    }
}
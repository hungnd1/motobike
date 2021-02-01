<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 25/02/2015
 * Time: 9:03 AM
 */

namespace api\models;

use api\controllers\ApiController;
use api\helpers\UserHelpers;
use common\models\site;
use common\models\Subscriber;
use Yii;
use yii\helpers\Url;

class Feature extends \common\models\Feature
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['display_name'] = function ($model) {
            /* @var $model \common\models\Feature */
            if(\Yii::$app->language == 'en'){
                return $model->display_name_en;
            }
            return $model->display_name;
        };


        return $fields;
    }

}
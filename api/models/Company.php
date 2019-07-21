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

class Company extends \common\models\Company
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['file'] = function ($model) {
            /* @var $model \common\models\Company */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };


        return $fields;
    }

}
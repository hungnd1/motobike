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
use Yii;
use yii\helpers\Url;

class YaraGap extends \common\models\YaraGap
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['content']);
        $fields['image'] = function ($model) {
            /* @var $model \common\models\YaraGap */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };


        return $fields;
    }

}
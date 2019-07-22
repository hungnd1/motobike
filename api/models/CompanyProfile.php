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

class CompanyProfile extends \common\models\CompanyProfile
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['ho_ten'] = function ($model) {
            /* @var $model \common\models\CompanyProfile */

            return ($model->ho . " " . $model->ten);
        };
        $fields['dia_chi'] = function ($model) {
            /* @var $model \common\models\CompanyProfile */
            return $model->thon_lang . ($model->huyen ? " - " . $model->huyen : "") . ($model->thanh_pho ? " - " . $model->thanh_pho : "");
        };


        return $fields;
    }

}
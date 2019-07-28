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
use common\models\ActorDirector;
use common\models\ContentActorDirectorAsm;
use common\models\ContentCategoryAsm;
use common\models\ContentProfile;
use common\models\ContentProfileSiteAsm;
use common\models\ContentSiteAsm;
use common\models\site;
use common\models\Subscriber;
use common\models\SubscriberContentAsm;
use Yii;
use yii\helpers\Url;

class CompanyNews extends \common\models\CompanyNews
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['content']);
        $fields['image'] = function ($model) {
            /* @var $model \common\models\CompanyNews */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };


        return $fields;
    }

}
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

class FileManage extends \common\models\FileManage
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['file'] = function ($model) {
            /* @var $model \common\models\FileManage */
            if($model->file){
                return $model->getFileLink();
            }
            return '';
        };
        return $fields;
    }

}
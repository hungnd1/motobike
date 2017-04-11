<?php
namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\AuthItem;
use common\models\LogLanguage;
use common\models\Multilanguage;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class BaseBEController extends Controller
{
 

    public $audit_id = null;

    public function behaviors()
    {
        return [
            'auth' => [
                'class' => Yii2Auth::className(),
                'autoAllow' => false,
//                'authManager' => 'authManager',
            ],
        ];
    }

//    public function init(){
//        //Here you can add specific code for generating Menu, but the code to change the Yii's default language
//        $is_default = Multilanguage::getLanguage();
//        Yii::$app->language = $is_default;
//
//    }
    public function init(){

    }
}

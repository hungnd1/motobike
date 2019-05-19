<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
use api\models\LogData;
use api\models\PriceCoffeeDetail;
use common\models\Answer;
use common\models\AppParam;
use common\models\Category;
use common\models\DeviceInfo;
use common\models\DeviceSubscriberAsm;
use common\models\Fruit;
use common\models\GameMini;
use common\models\GameMiniLog;
use common\models\GapGeneral;
use common\models\IsRating;
use common\models\MoMt;
use common\models\PriceCoffee;
use common\models\Province;
use common\models\Question;
use common\models\SiteApiCredential;
use common\models\Sold;
use common\models\Subscriber;
use common\models\SubscriberActivity;
use common\models\SubscriberServiceAsm;
use common\models\Term;
use common\models\TotalQuality;
use common\models\TypeCoffee;
use common\models\Version;
use common\models\WeatherDetail;
use Yii;
use yii\base\InvalidValueException;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

class MoMtController extends Controller
{
    public function actionReceiveMo($from, $to, $message,$requestid)
    {
        if (!$from) {
            throw new InvalidValueException('Số điện thoại gửi không được để trống');
        }
        if(!$to){
            throw  new InvalidValueException('Đầu số không được để trống');
        }

        if(!$message){
            throw  new InvalidValueException('Nội dung không được để trống');
        }

        if(!$requestid){
            throw  new InvalidValueException('Request ID không được để trống');
        }
        $momt = new MoMt();
        $momt->from_number = $from;
        $momt->to_number = $to;
        $momt->message_mo = $message;
        $momt->request_id = $requestid;
        $momt->status_sync = MoMt::STATUS_ACTIVE;
        $momt->status = MoMt::STATUS_ACTIVE;
        $momt->created_at = time();
        $momt->updated_at = time();
        $momt->save();
        header('Content-type: application/json');
        $arr = [
            'Sync' => true,
            'status' => 0,
            'message' => 'test'
        ];
        return json_encode($arr);
     }
}
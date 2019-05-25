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
use common\helpers\StringUtils;
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
use common\models\MtTemplate;
use common\models\PriceCoffee;
use common\models\Province;
use common\models\Question;
use common\models\SiteApiCredential;
use common\models\Sold;
use common\models\Station;
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
    public function actionReceiveMo($from, $to, $message, $requestid)
    {
        if (!$from) {
            throw new InvalidValueException('Số điện thoại gửi không được để trống');
        }
        if (!$to) {
            throw  new InvalidValueException('Đầu số không được để trống');
        }

        if (!$message) {
            throw  new InvalidValueException('Nội dung không được để trống');
        }

        if (!$requestid) {
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
        if(strtoupper($message) != 'DKGC'){
            $gia = explode("GIA", strtoupper(str_replace(" ", "", $message)));
            if($gia[1]){
                $message = str_replace("-","",str_replace("_","",$gia[1]));
            }
        }

        /** @var  $mtTemplate MtTemplate */
        $mtTemplate = MtTemplate::find()
            ->andWhere(['mo_key' => strtoupper($message)])
            ->andWhere(['status' => MtTemplate::STATUS_ACTIVE])
            ->one();
        if ($mtTemplate) {
            if($mtTemplate->station_code){
                /** @var  $stationCode Station */
                $stationCode = Station::find()->andWhere(['station_code'=>$mtTemplate->station_code])->one();
                /** @var  $province Province */
                $province = Province::find()->andWhere(['id'=>$stationCode->province_id])->one();
                $messageSuccess = str_replace("$1",$stationCode->station_name,$mtTemplate->content);
                $messageSuccess = str_replace("$2",$province->province_name_sms,$messageSuccess);
                $date = date('d/m/Y', time());
                $from_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
                $to_time = strtotime(str_replace('/', '-', $date) . ' 23:59:59');
                /** @var  $priceCoffee PriceCoffee */
                $priceCoffee = PriceCoffee::find()
                    ->andWhere(['>=', 'created_at', $from_time + 7 * 60 * 60])
                    ->andWhere(['<=', 'created_at', $to_time + 7 * 60 * 60])
                    ->andWhere(['province_id'=>$stationCode->station_code])
                    ->andWhere(['in', 'price_coffee.organisation_name', ['dACC', 'dACN', 'dRCL','dRBC','dRCC']])
                    ->one();
                if(!$priceCoffee){
                    $priceCoffee = PriceCoffee::find()
                        ->andWhere(['>=', 'created_at', $from_time + 7 * 60 * 60 - 86400])
                        ->andWhere(['<=', 'created_at', $to_time + 7 * 60 * 60 - 86400])
                        ->andWhere(['province_id'=>$stationCode->station_code])
                        ->andWhere(['in', 'price_coffee.organisation_name', ['dACC', 'dACN', 'dRCL','dRBC','dRCC']])
                        ->one();
                    if(!$priceCoffee){
                        $priceCoffee = PriceCoffee::find()
                            ->andWhere(['>=', 'created_at', $from_time + 7 * 60 * 60 - 2 * 86400])
                            ->andWhere(['<=', 'created_at', $to_time + 7 * 60 * 60 - 2 * 86400])
                            ->andWhere(['province_id'=>$stationCode->station_code])
                            ->andWhere(['in', 'price_coffee.organisation_name', ['dACC', 'dACN', 'dRCL','dRBC','dRCC']])
                            ->one();
                        if(!$priceCoffee){
                            $priceCoffee = PriceCoffee::find()
                                ->andWhere(['>=', 'created_at', $from_time + 7 * 60 * 60 - 3* 86400])
                                ->andWhere(['<=', 'created_at', $to_time + 7 * 60 * 60 - 3 * 86400])
                                ->andWhere(['province_id'=>$stationCode->station_code])
                                ->andWhere(['in', 'price_coffee.organisation_name', ['dACC', 'dACN', 'dRCL','dRBC','dRCC']])
                                ->one();
                        }
                    }
                }
                if($priceCoffee){
                    $messageSuccess = str_replace("$3",$priceCoffee->price_average,$messageSuccess);
                    $messageSuccess = str_replace("$4",$date, $messageSuccess);
                }else{
                    $messageSuccess = '';
                }
            }else{
                $messageSuccess = $mtTemplate->content;
            }
            if(!$messageSuccess){
                $arr = [
                    'Sync' => false,
                    'status' => 0,
                    'message' => "error"
                ];
            }else{
                $arr = [
                    'Sync' => true,
                    'status' => 0,
                    'message' => $messageSuccess
                ];
            }
            $momt->mt_template_id = $mtTemplate->id;
            $momt->save();
        } else {
            $arr = [
                'Sync' => false,
                'status' => 0,
                'message' => "error"
            ];
        }
        header('Content-type: application/json');

        return json_encode($arr);
    }
}
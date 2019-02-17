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
use common\models\Category;
use common\models\DeviceInfo;
use common\models\DeviceSubscriberAsm;
use common\models\Fruit;
use common\models\GapGeneral;
use common\models\IsRating;
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
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class AppController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
//            'check-device-token',
//            'get-price',
            'get-price-web',
            'get-price-mobile',
            'total-quality',
            'sold',
            'get-price-detail',
            'type-coffee',
            'get-category',
            'term',
            'log-data',
            'version-app',
            'gap-advice',
            'gap-advice-except',
            'get-introduce',
            'get-province',
            'accept-screen',
            'get-category-pet',
            'check-login'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'get-price' => ['GET'],
            'total-quantity' => ['GET'],
            'sold' => ['GET'],
            'type-coffee' => ['GET'],
            'get-category' => ['GET'],
            'term' => ['GET'],
//            'check-device-token' => ['POST'],
            'log-data' => ['GET'],
            'get-question' => ['GET'],
            'get-introduce' => ['GET']
        ];
    }

    public function actionCheckDeviceToken()
    {
        UserHelpers::manualLogin();

        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;

        $uid = $this->getParameterPost('device_token', '');
        $mac = $this->getParameterPost('mac', '');
        if (!$uid) {
            throw new InvalidValueException('Device token không được để trống');
        }
        if (!$mac) {
            throw new InvalidValueException('mac không được để trống');
        }
        $deviceInfo = DeviceInfo::findOne(['device_uid' => $uid]);
        //kiem tra neu device map voi subcriber roi
        /** @var  $deviceSubscriberAsm DeviceSubscriberAsm */
        $deviceSubscriberAsm = DeviceSubscriberAsm::find()->andWhere(['subscriber_id' => $subscriber->id])->one();
        if (!$deviceInfo) {
            $device = new DeviceInfo();
            $device->device_uid = $uid;
            $device->device_type = $this->type;
            $device->created_at = time();
            $device->updated_at = time();
            $device->mac = $mac;
            $device->status = DeviceInfo::STATUS_ACTIVE;
            $device->save();
            if ($deviceSubscriberAsm) {
                $deviceSubscriberAsm->device_id = $device->id;
                $deviceSubscriberAsm->updated_at = time();
                $deviceSubscriberAsm->save();
            } else {
                $deviceSubscriberAsm = new DeviceSubscriberAsm();
                $deviceSubscriberAsm->device_id = $device->id;
                $deviceSubscriberAsm->subscriber_id = $subscriber->id;
                $deviceSubscriberAsm->created_at = time();
                $deviceSubscriberAsm->updated_at = time();
                $deviceSubscriberAsm->save();
            }
        } else {
            if ($deviceSubscriberAsm) {
                $deviceSubscriberAsm->device_id = $deviceInfo->id;
                $deviceSubscriberAsm->updated_at = time();
                $deviceSubscriberAsm->save();
            } else {
                $deviceSubscriberAsm = new DeviceSubscriberAsm();
                $deviceSubscriberAsm->device_id = $deviceInfo->id;
                $deviceSubscriberAsm->subscriber_id = $subscriber->id;
                $deviceSubscriberAsm->created_at = time();
                $deviceSubscriberAsm->updated_at = time();
                $deviceSubscriberAsm->save();
            }
        }

        return true;
    }

    public function actionGetPrice($date = 0, $coffee = PriceCoffee::TYPE_GIASAN)
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        if ($subscriber) {
            /** @var  $lastActivity SubscriberActivity */
            $lastActivity = SubscriberActivity::find()->andWhere(['action' => SubscriberActivity::ACTION_PRICE])->orderBy(['id' => SORT_DESC])->one();
            if ($lastActivity) {
                if (time() - $lastActivity->created_at >= 5 * 60) {
                    $description = 'Nguoi dung vao gia';
                    $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_PRICE, $description);
                }
            } else {
                $description = 'Nguoi dung vao gia';
                $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_PRICE, $description);
            }
            $isRating = IsRating::addIsRating(SubscriberActivity::ACTION_PRICE, $subscriber->id);
        }
        if (!$date) {
            $date = date('d/m/Y', time());
        }
        $arr = [];
//        $from_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
//        $to_time = strtotime(str_replace('/', '-', $date) . ' 23:59:59');
        //11_110_11000 gia san london
        //10_100_10000 gia san neư york
        if ($coffee == PriceCoffee::TYPE_GIASAN) {
            $arr_province = [];
            $arr_province['province_name'] = Yii::t('app', 'Giá sàn');
            $arr_province['price'] = PriceCoffee::getPrice($date, null, PriceCoffee::TYPE_EXPORT);
            $arr[] = $arr_province;
        } else {
            if ($coffee == PriceCoffee::TYPE_QUATUOIVOI) {
                $key = ['dRCA', 'dRCF', 'dRCC'];
            } elseif ($coffee == PriceCoffee::TYPE_QUATUOICHE) {
                $key = ['dACF', 'dACC', 'dACA'];
            } elseif ($coffee == PriceCoffee::TYPE_NHANXOCHE) {
                $key = ['dABA', 'dABC', 'dABF'];
            } else {
                $key = ['dRBA', 'dRBC', 'dRBF'];
            }
            $provinces = Province::find()->andWhere('province_code <> :province_code', ['province_code' => 62])->all();
            foreach ($provinces as $item) {
                $arr_province = [];
                $arr_province['province_name'] = $item->province_name;
                $arr_province['price'] = $listPrice = PriceCoffee::getPrice($date, $item->id, PriceCoffee::TYPE_NORMAL, $key);
                $arr[] = $arr_province;
            }
        }
        return $arr['items'] = $arr;
    }

    public function actionGetPriceWeb($date = 0, $coffee = PriceCoffee::TYPE_GIASAN)
    {
        if (!$date) {
            $date = date('d/m/Y', time());
        }
        $arr = [];
//        $from_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
//        $to_time = strtotime(str_replace('/', '-', $date) . ' 23:59:59');
        //11_110_11000 gia san london
        //10_100_10000 gia san neư york
        if ($coffee == PriceCoffee::TYPE_GIASAN) {
            $arr_province = [];
            $arr_province['province_name'] = Yii::t('app', 'Giá sàn');
            $arr_province['price'] = PriceCoffee::getPrice($date, null, PriceCoffee::TYPE_EXPORT);
            $arr[] = $arr_province;
        } else {
            if ($coffee == PriceCoffee::TYPE_QUATUOIVOI) {
                $key = ['dRCA', 'dRCF', 'dRCC'];
            } elseif ($coffee == PriceCoffee::TYPE_QUATUOICHE) {
                $key = ['dACF', 'dACC', 'dACA'];
            } elseif ($coffee == PriceCoffee::TYPE_NHANXOCHE) {
                $key = ['dABA', 'dABC', 'dABF'];
            } else {
                $key = ['dRBA', 'dRBC', 'dRBF'];
            }
            $provinces = Province::find()->andWhere('province_code <> :province_code', ['province_code' => 62])->all();
            foreach ($provinces as $item) {
                $arr_province = [];
                $arr_province['province_name'] = $item->province_name;
                $arr_province['price'] = $listPrice = PriceCoffee::getPrice($date, $item->id, PriceCoffee::TYPE_NORMAL, $key);
                $arr[] = $arr_province;
            }
        }
        return $arr['items'] = $arr;
    }

    public function actionTotalQuantity()
    {
        $query = TotalQuality::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionSold()
    {
        $query = Sold::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetProvince()
    {
        $query = Province::find()
            ->andWhere(['<>', 'id', 4]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionTypeCoffee()
    {
        $query = TypeCoffee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetPriceDetail()
    {
        $organisation_name = $this->getParameter('organisation_name', '');
        $province_id = $this->getParameter('province_id', '');
        $date = $this->getParameter('date', 0);
        if (!$organisation_name) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'coffee_old_id')]));
        }

        if (!$province_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'coffee_old_id')]));
        }

        if (!$date) {
            $date = date('d/m/Y', time());
        }

        $to_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
        $from_time = $to_time - 86400 * 29;
        $pricePre = PriceCoffeeDetail::find()
            ->andWhere(['>=', 'created_at', $from_time + 7 * 60 * 60])
            ->andWhere(['<=', 'created_at', $to_time + 7 * 60 * 60])
            ->andWhere(['province_id' => $province_id])
            ->andWhere(['organisation_name' => $organisation_name])
            ->groupBy('created_at')
            ->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $pricePre,
            'pagination' => false,
        ]);
        return $dataProvider;

    }

    public function actionGetCategory($fruit_id = Fruit::COFFEE)
    {
        $query = \api\models\Category::find()
            ->andWhere(['status' => Category::STATUS_ACTIVE])
            ->andWhere(['fruit_id' => $fruit_id])
            ->orderBy(['order_number' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionTerm()
    {
        $query = Term::find()->orderBy(['updated_at' => SORT_DESC])->limit(1);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionLogData()
    {
        $query = LogData::find()->andWhere('latitude is not null')
            ->groupBy(['latitude', 'longitude']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $dataProvider;
    }

    public function actionVersionApp($type = Version::TYPE_ANDROID)
    {
        $version = Version::find()->andWhere(['type' => $type])->orderBy(['created_at' => SORT_DESC])->one();
        if ($version) {
            return [
                'message' => Yii::t('app', 'Hiện tại app đã ra phiển bản mới bạn vui lòng cập nhật để tiếp tục sử dụng'),
                'items' => $version
            ];
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGapAdvice($fruit_id = 5, $tem = 0, $pre = 0, $wind = 0)
    {

        UserHelpers::manualLogin();

        $today = strtotime('today midnight');
        $tomorrow = strtotime('tomorrow');

        $subscriber = Yii::$app->user->identity;
        /** @var  $subscriber Subscriber */
        /** @var  $subscriberServiceAsm  SubscriberServiceAsm */

        if ($tem || $pre || $wind) {
            $wind = $wind * 3.6;
        } else {
            $sql = "select station_code, (((acos(sin((" . Yii::$app->request->headers->get(static::HEADER_LATITUDE) . "*pi()/180)) * 
            sin((`latitude`*pi()/180))+cos((" . Yii::$app->request->headers->get(static::HEADER_LATITUDE) . "*pi()/180)) *
            cos((`latitude`*pi()/180)) * cos(((" . Yii::$app->request->headers->get(static::HEADER_LONGITUDE) . "- `longtitude`)*pi()/180))))*180/pi())*60*1.1515) 
            as distance
            FROM station where latitude is not null and longtitude is not null  order by distance asc limit 1";
            $connect = Yii::$app->getDb();
            $command = $connect->createCommand($sql);
            $result = $command->queryAll();
            $stationCode = $result[0]['station_code'];
            /** @var  $weatherDetail WeatherDetail */
            $weatherDetail = WeatherDetail::find()
                ->andWhere(['station_code' => $stationCode])
                ->andWhere(['>=', 'timestamp', $today])
                ->andWhere(['<', 'timestamp', $tomorrow])
                ->one();
            if (!$weatherDetail) {
                $this->setStatusCode(407);
                return [
                    'message' => 'Bạn vui lòng xem thông tin thời tiết xã mà bạn muốn xem trước khi vào khuyến cáo thông minh'
                ];
            }
            if ($subscriber->weather_detail_id) {
                if ($subscriber->weather_detail_id != $weatherDetail->station_code) {
                    $subscriber->weather_detail_id = $weatherDetail->station_code;
                    $subscriber->save(false);
                }
            }
            $wind = $weatherDetail ? 3.6 * $weatherDetail->wndspd : 2 * 3.6;
            $tem = $weatherDetail ? round(($weatherDetail->tmax + $weatherDetail->tmin) / 2, 1) : 25;
            $pre = $weatherDetail ? $weatherDetail->precipitation : 8;
        }

//        if ($subscriber) {
//            $subscriberServiceAsm = SubscriberServiceAsm::find()
//                ->andWhere(['subscriber_id' => $subscriber->id])
//                ->andWhere(['status' => SubscriberServiceAsm::STATUS_ACTIVE])
//                ->orderBy(['updated_at' => SORT_DESC])->one();
//            if ($subscriberServiceAsm) {
//                if ($subscriberServiceAsm->time_expired - time() < 0) {
//                    $this->setStatusCode(406);
//                    return [
//                        'message' => 'Gói cước của bạn đã hết hạn. Vui lòng gia gói cước mới'
//                    ];
//                }
//            } else {
//                $this->setStatusCode(405);
//                return [
//                    'message' => 'Bạn chưa đăng ký mua gói'
//                ];
//            }
//        }

        $gapAdvice = GapGeneral::find()
            ->andWhere(['type' => GapGeneral::GAP_DETAIL])
            ->andWhere(['fruit_id' => $fruit_id])
            ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
            ->andWhere('temperature_max > :temp', [':temp' => $tem])
            ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
            ->andWhere('precipitation_max >= :prep', [':prep' => $pre])
            ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
            ->andWhere('windspeed_max >= :wind1', [':wind1' => $wind])->one();

        if (!$gapAdvice) {
            $gapAdvice = GapGeneral::find()
                ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                ->andWhere(['fruit_id' => $fruit_id])
                ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                ->andWhere('temperature_max > :temp', [':temp' => $tem])
                ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                ->andWhere('precipitation_min != :pre1', [':pre1' => 0])
                ->andWhere(['precipitation_max' => 0])
                ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                ->andWhere('windspeed_max >= :wind1', [':wind1' => $wind])->one();

            if (!$gapAdvice) {
                $gapAdvice = GapGeneral::find()
                    ->andWhere(['fruit_id' => $fruit_id])
                    ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                    ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                    ->andWhere('temperature_max > :temp', [':temp' => $tem])
                    ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                    ->andWhere('precipitation_max >= :prep', [':prep' => $pre])
                    ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                    ->andWhere('windspeed_min !=  :wind1', [':wind1' => 0])
                    ->andWhere(['windspeed_max' => 0])->one();
                if (!$gapAdvice) {
                    $gapAdvice = GapGeneral::find()
                        ->andWhere(['fruit_id' => $fruit_id])
                        ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                        ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                        ->andWhere('temperature_max > :temp', [':temp' => $tem])
                        ->andWhere('precipitation_min != :pre1', [':pre1' => 0])
                        ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                        ->andWhere(['precipitation_max' => 0])
                        ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                        ->andWhere('windspeed_min !=  :wind1', [':wind1' => 0])
                        ->andWhere(['windspeed_max' => 0])->one();

                }
            }
        }
        /** @var $gapAdvice GapGeneral */
        if ($gapAdvice) {
            $res = array();
            $arr_item = array();
            if ($fruit_id == 5 || $fruit_id == 6) {
                array_push($arr_item, [
                    'content' => $gapAdvice->gap,
                    'tag' => Yii::t('app', 'Làm đất'),
                    'is_question' => false
                ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_8,
                        'tag' => Yii::t('app', 'Chuẩn bị giống - vườn ươm'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_2,
                        'tag' => Yii::t('app', 'Trồng mới, trồng lại và chăm sóc cà phê'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_3,
                        'tag' => Yii::t('app', 'Phân bón'),
                        'is_question' => true
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_5,
                        'tag' => Yii::t('app', 'Phun thuốc'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_4,
                        'tag' => Yii::t('app', 'Tưới nước'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_9,
                        'tag' => Yii::t('app', 'Tạo hình'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_6,
                        'tag' => Yii::t('app', 'Thu hoạch'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_7,
                        'tag' => Yii::t('app', 'Sơ chế'),
                        'is_question' => false
                    ]);
            } elseif ($fruit_id == 2) {
                array_push($arr_item, [
                    'content' => $gapAdvice->gap,
                    'tag' => Yii::t('app', 'Làm đất, chuẩn bị hố và trồng tiêu'),
                    'is_question' => false
                ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_8,
                        'tag' => Yii::t('app', 'Chọn lựa và trồng choái cho tiêu leo'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_2,
                        'tag' => Yii::t('app', 'Chăm sóc thường xuyên tiêu từ năm một đến năm ba.'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_3,
                        'tag' => Yii::t('app', 'Chăm sóc thường xuyên tiêu kinh doanh'),
                        'is_question' => true
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_5,
                        'tag' => Yii::t('app', 'Đôn tiêu'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_4,
                        'tag' => Yii::t('app', 'Phòng trừ sâu bệnh cho tiêu kinh doanh'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_9,
                        'tag' => Yii::t('app', 'Thu hái'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_6,
                        'tag' => Yii::t('app', 'Sơ chế bảo quản'),
                        'is_question' => false
                    ]);
            } else if ($fruit_id == 3) {
                array_push($arr_item, [
                    'content' => $gapAdvice->gap,
                    'tag' => Yii::t('app', 'Chọn đất, mật độ, đào hố, bón lót'),
                    'is_question' => false
                ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_2,
                        'tag' => Yii::t('app', 'Trồng sầu riêng'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_3,
                        'tag' => Yii::t('app', 'Bón phân'),
                        'is_question' => true
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_5,
                        'tag' => Yii::t('app', 'Phun thuốc'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_4,
                        'tag' => Yii::t('app', 'Tưới nước'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_9,
                        'tag' => Yii::t('app', 'Tỉa cành, tạo hình'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_6,
                        'tag' => Yii::t('app', 'Thu hái, bảo quản'),
                        'is_question' => false
                    ]);
            } else if ($fruit_id == 4) {
                array_push($arr_item, [
                    'content' => $gapAdvice->gap,
                    'tag' => Yii::t('app', 'Chọn đất, mật độ, đào hố, bón lót'),
                    'is_question' => false
                ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_2,
                        'tag' => Yii::t('app', 'Trồng bơ'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_3,
                        'tag' => Yii::t('app', 'Bón phân'),
                        'is_question' => true
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_5,
                        'tag' => Yii::t('app', 'Phun thuốc'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_4,
                        'tag' => Yii::t('app', 'Tưới nước'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_9,
                        'tag' => Yii::t('app', 'Tỉa cành, tạo hình'),
                        'is_question' => false
                    ]);
                array_push($arr_item,
                    [
                        'content' => $gapAdvice->content_6,
                        'tag' => Yii::t('app', 'Thu hái, bảo quản'),
                        'is_question' => false
                    ]);
            }
            $res['items'] = $arr_item;
            $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_NONG_NGHIEP_THONG_MINH, 'Nong nghiep thong minh');
            $isRating = IsRating::addIsRating(SubscriberActivity::ACTION_NONG_NGHIEP_THONG_MINH, $subscriber->id);

            return $res;
        } else {
            throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
        }
    }

    public function actionGetQuestion($fruit_id = 5)
    {

        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        /** @var  $subscriber Subscriber */

        $listQuestion = Question::find()
            ->andWhere(['fruit_id' => $fruit_id])
            ->all();
        $arrRes = [];
        $res = [];
        $arrQues = [];
        foreach ($listQuestion as $question) {
            /** @var $question Question */
            $arrAnswer = [];
            $resAnswer = [];
            $listAnswer = Answer::find()->andWhere(['question_id' => $question->id])->all();
            foreach ($listAnswer as $answer) {
                /** @var $answer Answer */
                array_push($arrAnswer, $answer);
            }
            $resAnswer['items'] = $arrAnswer;
            $arrRes['id'] = $question->id;
            $arrRes['question'] = $question->question;
            $arrRes['is_dropdown_list'] = $question->is_dropdown_list;
            if (empty($resAnswer['items'])) {
                $arrRes['answer'] = null;
            } else {
                $arrRes['answer'] = $resAnswer;
            }
            array_push($arrQues, $arrRes);
        }
        $res['items'] = $arrQues;

        $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_TU_VAN_SU_DUNG, 'Tu van su dung phan bong');
        $isRating = IsRating::addIsRating(SubscriberActivity::ACTION_TU_VAN_SU_DUNG, $subscriber->id);


        return $res;
    }

    public function actionGetIntroduce()
    {
        $res = array();
        $cache = Yii::$app->cache;
        $key = Yii::$app->params['key_cache']['Introduce'] . $this->language;
        $res = $cache->get($key);
        if ($res === false) {
            $arr_item = array();
            array_push($arr_item, [
                'content' => Yii::t('app', "Greencoffee xin chào,\n chúc một ngày tốt lành!"),
                'type' => 1
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', 'Chúng tôi xin gửi đến bạn dự báo thời tiết tại địa bàn của bạn hôm nay như sau:'),
                'type' => 2
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', 'Chúng tôi xin gửi đến bạn chi tiết về dự báo thời tiết tại địa bàn trong tuần như sau:'),
                'type' => 3
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', "Trong điều kiện thời tiết hôm nay, chúng tôi xin gửi đến bạn một số tư vấn tham khảo về các công việc chính trên vườn cây."),
                'type' => 4
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', 'Với loại đất trồng và năng suất dự kiến như vậy, chúng tôi xin gửi đến bạn đôi lời tư vấn về quản lý và sử dụng phân bón hiệu quả dưới đây:'),
                'type' => 5
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', 'Chúng tôi xin gửi đến bạn tình hình sâu bệnh trên địa bàn các tỉnh Tây Nguyên trong tuần như sau:'),
                'type' => 6
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', 'Với tuổi cây đối với tiêu KTCB và kích thước cây đối với Tiêu kinh doanh, chúng tôi khuyến cáo sử dụng phân như sau:'),
                'type' => 7
            ]);
            array_push($arr_item, [
                'content' => Yii::t('app', 'Với tuổi cây như đã chọn, chúng tôi khuyến cáo sử dụng bón phân như sau:'),
                'type' => 8
            ]);
            $res['items'] = $arr_item;
            $cache->set($key, $res, Yii::$app->params['time_expire_cache'], new TagDependency(['tags' => Yii::$app->params['key_cache']['Introduce']]));
        }
        return $res;
    }

    public function actionGapAdviceExcept($tem = 0, $pre = 0, $wind = 0)
    {

        $wind = 3.6 * $wind;

        $gapAdvice = GapGeneral::find()
            ->andWhere(['type' => GapGeneral::GAP_DETAIL])
            ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
            ->andWhere('temperature_max > :temp', [':temp' => $tem])
            ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
            ->andWhere('precipitation_max >= :prep', [':prep' => $pre])
            ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
            ->andWhere('windspeed_max >= :wind1', [':wind1' => $wind])->one();

        if (!$gapAdvice) {
            $gapAdvice = GapGeneral::find()
                ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                ->andWhere('temperature_max > :temp', [':temp' => $tem])
                ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                ->andWhere('precipitation_min != :pre1', [':pre1' => 0])
                ->andWhere(['precipitation_max' => 0])
                ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                ->andWhere('windspeed_max >= :wind1', [':wind1' => $wind])->one();

            if (!$gapAdvice) {
                $gapAdvice = GapGeneral::find()
                    ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                    ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                    ->andWhere('temperature_max > :temp', [':temp' => $tem])
                    ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                    ->andWhere('precipitation_max >= :prep', [':prep' => $pre])
                    ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                    ->andWhere('windspeed_min !=  :wind1', [':wind1' => 0])
                    ->andWhere(['windspeed_max' => 0])->one();
                if (!$gapAdvice) {
                    $gapAdvice = GapGeneral::find()
                        ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                        ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                        ->andWhere('temperature_max > :temp', [':temp' => $tem])
                        ->andWhere('precipitation_min != :pre1', [':pre1' => 0])
                        ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                        ->andWhere(['precipitation_max' => 0])
                        ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                        ->andWhere('windspeed_min !=  :wind1', [':wind1' => 0])
                        ->andWhere(['windspeed_max' => 0])->one();

                }
            }
        }
        /** @var $gapAdvice GapGeneral */
        if ($gapAdvice) {
            $res = array();
            $arr_item = array();
            array_push($arr_item, [
                'content' => $gapAdvice->gap,
                'tag' => Yii::t('app', 'Làm đất'),
                'is_question' => false
            ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_8,
                    'tag' => Yii::t('app', 'Chuẩn bị giống - vườn ươm'),
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_2,
                    'tag' => Yii::t('app', 'Trồng mới, trồng lại và chăm sóc cà phê'),
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_3,
                    'tag' => Yii::t('app', 'Phân bón'),
                    'is_question' => true
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_5,
                    'tag' => Yii::t('app', 'Phun thuốc'),
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_4,
                    'tag' => Yii::t('app', 'Tưới nước'),
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_9,
                    'tag' => Yii::t('app', 'Tạo hình'),
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_6,
                    'tag' => Yii::t('app', 'Thu hoạch'),
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_7,
                    'tag' => Yii::t('app', 'Sơ chế'),
                    'is_question' => false
                ]);
            $res['items'] = $arr_item;

            return $res;
        } else {
            throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
        }
    }

    public function actionGetMessageAdvice($id)
    {
        if ($id == 1) {
            return [
                'message' => Yii::t('app', 'Trong điều kiện thời tiết hôm nay chúng tôi xin gửi đến bạn một số thông tin tham khảo và các công việc chính cho CÂY CÀ PHÊ.')
            ];
        } else if ($id == 2) {
            return [
                'message' => Yii::t('app', 'Trong điều kiện thời tiết hôm nay chúng tôi xin gửi đến bạn một số thông tin tham khảo và các công việc chính cho CÂY HỒ TIÊU.')
            ];
        } else if ($id == 3) {
            return [
                'message' => Yii::t('app', 'Trong điều kiện thời tiết hôm nay chúng tôi xin gửi đến bạn một số thông tin tham khảo và các công việc chính cho CÂY SẦU RIÊNG.')
            ];
        } else if ($id == 4) {
            return [
                'message' => Yii::t('app', 'Trong điều kiện thời tiết hôm nay chúng tôi xin gửi đến bạn một số thông tin tham khảo và các công việc chính cho CÂY BƠ.')
            ];
        } else if ($id == 5) {
            return [
                'message' => Yii::t('app', 'Trong điều kiện thời tiết hôm nay chúng tôi xin gửi đến bạn một số thông tin tham khảo và các công việc chính cho CÀ PHÊ ROBUSTA (vối).')
            ];
        } else if ($id == 6) {
            return [
                'message' => Yii::t('app', 'Trong điều kiện thời tiết hôm nay chúng tôi xin gửi đến bạn một số thông tin tham khảo và các công việc chính cho CÀ PHÊ ARABICA (chè).')
            ];
        }
    }

    public function actionAcceptScreen()
    {
        $this->setStatusCode(501);
        return [
            'message' => 'OK',
            'is_screen' => true
        ];
    }

    public function actionGetCategoryPet()
    {
        $res = array();
        $arr_item = array();
        array_push($arr_item, [
            'image' => "http://45.32.112.173:84/static/news/benh-gi-sat-tren-cay-ca-phe.png",
            'id' => 1,
            'title' => 'Sâu bệnh'
        ]);
        array_push($arr_item, [
            'image' => "http://45.32.112.173:84/static/news/benh-gi-sat-tren-cay-ca-phe.png",
            'id' => 2,
            'title' => 'Tin tức'
        ]);
        array_push($arr_item, [
            'image' => "http://45.32.112.173:84/static/news/benh-gi-sat-tren-cay-ca-phe.png",
            'id' => 3,
            'title' => 'Sự kiện'
        ]);
        $res['items'] = $arr_item;
        return $res;
    }

    public function actionCheckLogin($mac){
        /** @var  $subscriber Subscriber */
        $subscriber = Subscriber::find()
            ->innerJoin('device_subscriber_asm','device_subscriber_asm.subscriber_id = subscriber.id')
            ->innerJoin('device_info','device_subscriber_asm.device_id = device_info.id')
            ->andWhere(['device_info.mac'=>$mac])
            ->one();
        if($subscriber && $subscriber->full_name && $subscriber->age && $subscriber->sex && $subscriber->address){
            $this->setStatusCode(200);
        } else{
            $this->setStatusCode(501);
        }
        return [
            'message' => 'Ok'
        ];
    }
}
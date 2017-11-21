<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\models\LogData;
use api\models\PriceCoffeeDetail;
use common\models\Answer;
use common\models\Category;
use common\models\DeviceInfo;
use common\models\GapGeneral;
use common\models\PriceCoffee;
use common\models\Province;
use common\models\Question;
use common\models\Sold;
use common\models\Term;
use common\models\TotalQuality;
use common\models\TypeCoffee;
use common\models\Version;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
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
            'check-device-token',
            'get-price',
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
            'get-question'
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
            'check-device-token' => ['POST'],
            'log-data' => ['GET'],
            'get-question' => ['GET']
        ];
    }

    public function actionCheckDeviceToken()
    {
        $uid = $this->getParameterPost('device_token', '');
        $type = $this->getParameterPost('channel', DeviceInfo::TYPE_ANDROID);
        $mac = $this->getParameterPost('mac', '');
        if (!$uid) {
            throw new InvalidValueException('Device token không được để trống');
        }
        if (!$mac) {
            throw new InvalidValueException('mac không được để trống');
        }
        $deviceInfo = DeviceInfo::findOne(['device_type' => $type, 'device_uid' => $uid]);
        if (!$deviceInfo) {
            $device = new DeviceInfo();
            $device->device_uid = $uid;
            $device->device_type = $type;
            $device->created_at = time();
            $device->updated_at = time();
            $device->mac = $mac;
            $device->status = DeviceInfo::STATUS_ACTIVE;
            $device->save();
        }

        return true;
    }

    public function actionGetPrice($date = 0, $coffee = PriceCoffee::TYPE_GIASAN)
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
            $arr_province['province_name'] = 'Giá sàn';
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

    public function actionGetCategory()
    {
        $query = Category::find()->andWhere(['status' => Category::STATUS_ACTIVE])->orderBy(['order_number' => SORT_DESC]);

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
                'message' => 'Hiện tại app đã ra phiển bản mới bạn vui lòng cập nhật để tiếp tục sử dụng',
                'items' => $version
            ];
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }

    public function actionGapAdvice($tem = 0, $pre = 0, $wind = 0)
    {
        $wind = floor($wind * 1000 / 60);

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
                ->andWhere('precipitation_max <= :pre', [':pre' => $pre])
                ->andWhere('precipitation_max != :pre1', [':pre1' => 0])
                ->andWhere(['precipitation_min' => 0])
                ->andWhere('windspeed_min <= :wind', [':wind' => $wind])
                ->andWhere('windspeed_max > :wind1', [':wind1' => $wind])->one();

            if (!$gapAdvice) {
                $gapAdvice = GapGeneral::find()
                    ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                    ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                    ->andWhere('temperature_max > :temp', [':temp' => $tem])
                    ->andWhere('precipitation_min <= :pre', [':pre' => $pre])
                    ->andWhere('precipitation_max > :prep', [':prep' => $pre])
                    ->andWhere('windspeed_max <= :wind', [':wind' => $wind])
                    ->andWhere('windspeed_max !=  :wind1', [':wind1' => 0])
                    ->andWhere(['windspeed_min' => 0])->one();
                if (!$gapAdvice) {
                    $gapAdvice = GapGeneral::find()
                        ->andWhere(['type' => GapGeneral::GAP_DETAIL])
                        ->andWhere('temperature_min <= :tem ', [':tem' => $tem])
                        ->andWhere('temperature_max > :temp', [':temp' => $tem])
                        ->andWhere('precipitation_max != :pre1', [':pre1' => 0])
                        ->andWhere('precipitation_max <= :pre', [':pre' => $pre])
                        ->andWhere(['precipitation_min' => 0])
                        ->andWhere('windspeed_max <= :wind', [':wind' => $wind])
                        ->andWhere('windspeed_max !=  :wind1', [':wind1' => 0])
                        ->andWhere(['windspeed_min' => 0])->one();

                }
            }
        }
        /** @var $gapAdvice GapGeneral */
        if ($gapAdvice) {
            $res = array();
            $arr_item = array();
            array_push($arr_item, [
                'content' => $gapAdvice->gap,
                'tag' => 'Làm đất',
                'is_question' => false
            ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_8,
                    'tag' => 'Chuẩn bị giống - vườn ươm',
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_2,
                    'tag' => 'Trồng mới, trồng lại và chăm sóc cà phê',
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_3,
                    'tag' => 'Phân bón',
                    'is_question' => true
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_5,
                    'tag' => 'Phun thuốc',
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_4,
                    'tag' => 'Tưới nước',
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_9,
                    'tag' => 'Tạo hình',
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_6,
                    'tag' => 'Thu hoạch',
                    'is_question' => false
                ]);
            array_push($arr_item,
                [
                    'content' => $gapAdvice->content_7,
                    'tag' => 'Sơ chế',
                    'is_question' => false
                ]);
            $res['items'] = $arr_item;

            return $res;
        } else {
            throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
        }
    }

    public function actionGetQuestion()
    {
        $listQuestion = Question::find()->all();
        $arrRes = [];
        $res = [];
        $arrQues = [];
        foreach ($listQuestion as $question) {
            /** @var $question Question */
            $arrAnswer = [];
            $resAnswer = [];
            if ($question->is_dropdown_list) {
                $listAnswer = Answer::find()->andWhere(['question_id' => $question->id])->all();
                foreach ($listAnswer as $answer) {
                    /** @var $answer Answer */
                    array_push($arrAnswer, $answer);
                }
                $resAnswer['items'] = $arrAnswer;
            }
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
        return $res;
    }
}
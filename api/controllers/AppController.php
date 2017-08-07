<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\models\PriceCoffeeDetail;
use common\models\Category;
use common\models\DeviceInfo;
use api\models\LogData;
use common\models\PriceCoffee;
use common\models\Sold;
use common\models\Term;
use common\models\TotalQuality;
use common\models\TypeCoffee;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;

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
            'total-quality',
            'sold',
            'get-price-detail',
            'type-coffee',
            'get-category',
            'term',
            'log-data'
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
            'log-data' => ['GET']
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

    public function actionGetPrice($date = 0)
    {
        if (!$date) {
            $date = date('d/m/Y', time());
        }
        $listPrice = PriceCoffee::getPrice($date);
        if ($listPrice->query->one()) {
            return $listPrice;
        }
        $this->setStatusCode(500);
        return ['message' => 'Hệ thống chưa cập nhật giá'];
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
        $coffee_old_id = $this->getParameter('coffee_old_id', '');
        $date = $this->getParameter('date', 0);
        if (!$coffee_old_id) {
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
            ->andWhere(['coffee_old_id' => $coffee_old_id])
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
        $query = LogData::find()->andWhere('latitude is not null');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $dataProvider;
    }
}
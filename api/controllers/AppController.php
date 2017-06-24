<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */

namespace api\controllers;


use common\models\Category;
use common\models\Content;
use common\models\DeviceInfo;
use common\models\PriceCoffee;
use common\models\ServiceGroup;
use common\models\Sold;
use common\models\TotalQuality;
use common\models\TypeCoffee;
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
            'type-coffee'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'get-price' => ['GET'],
            'total-quality' => ['GET'],
            'sold' => ['GET'],
            'type-coffee' => ['GET'],
            'check-device-token' => ['POST']
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
            $date = date('d/m/Y',time());
        }

        $listPrice = PriceCoffee::getPrice($date);


        return $listPrice;
    }

    public function actionTotalQuality(){
        $query = TotalQuality::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionSold(){
        $query = Sold::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionTypeCoffee(){
        $query = TypeCoffee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }
}
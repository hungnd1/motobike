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
use common\models\Station;
use common\models\Term;
use common\models\TotalQuality;
use common\models\TypeCoffee;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;

class LogDataController extends ApiExceptLoginController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'log-data',
            'get-list-station',
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'log-data' => ['GET'],
            'get-list-station' => ['GET'],
        ];
    }


    public function actionLogData()
    {
        $query = LogData::find()->andWhere('latitude is not null')
        ->groupBy(['latitude','longitude']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $dataProvider;
    }

    public function actionGetListStation()
    {

        $query = Station::find()
            ->andWhere(['status' => Station::STATUS_ACTIVE])
            ->andWhere('latitude is not null');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['station_name' => SORT_ASC],
            ],
        ]);
        return $dataProvider;

    }
}
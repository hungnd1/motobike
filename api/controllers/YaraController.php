<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use api\helpers\UserHelpers;
use api\models\Station;
use common\helpers\CVietnameseTools;
use common\models\SubscriberActivity;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class YaraController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'search',
//            'get-list-station',
            'get-location'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-station'
        ];
    }

    public function actionGetListSupplier()
    {
        UserHelpers::manualLogin();
        $query = \api\models\YaraSupplier::find()
            ->andWhere('longitude is not null')
            ->andWhere('latitude is not null');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['ma' => SORT_ASC],
            ],
        ]);
        return $dataProvider;

    }
}
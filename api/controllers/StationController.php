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

class StationController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'search'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-station'
        ];
    }

    public function actionGetListStation()
    {
        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        $description = 'Nguoi dung vao thoi tiet';
        $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_WEATHER, $description);
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

    public function actionSearch($keyword = '')
    {
        $query = Station::find()->andWhere(['status' => Station::STATUS_ACTIVE])
            ->andWhere('latitude is not null')
            ->andWhere(['like', 'lower(station_name)', CVietnameseTools::removeSigns((strtolower($keyword)))])
            ->orWhere(['like', 'lower(station_name)', strtolower($keyword)]);
        $defaultSort = ['station_name' => SORT_ASC];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 1000],
            ],
            'sort' => [
                'defaultOrder' => $defaultSort,
            ],
        ]);
        return $dataProvider;
    }
}
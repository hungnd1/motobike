<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use common\models\Station;
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
            'get-list-station'
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

        $query = Station::find()->andWhere(['status' => Station::STATUS_ACTIVE]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 1000],
            ],
            'sort' => [
                'defaultOrder' => ['station_name' => SORT_ASC],
            ],
        ]);
        return $dataProvider;

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use api\models\Station;
use common\helpers\CVietnameseTools;
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
            'get-list-station',
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

        $query = Station::find()->andWhere(['status' => Station::STATUS_ACTIVE]);
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
            ->andWhere(['like', 'station_name', CVietnameseTools::removeSigns($keyword)])
            ->orWhere(['like', 'station_name', $keyword]);
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
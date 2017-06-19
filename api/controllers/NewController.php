<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use api\models\News;
use Yii;
use yii\data\ActiveDataProvider;

class NewController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'get-list-new'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-new'
        ];
    }

    public function actionGetListNew()
    {
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = News::find()->andWhere(['status' => News::STATUS_ACTIVE]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);
        return $dataProvider;

    }
}
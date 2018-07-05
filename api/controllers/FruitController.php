<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/23/2018
 * Time: 9:17 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\models\Fruit;
use common\models\Feature;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;

class FruitController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'get-fruit',
            'get-group',
            'get-feature',
            'get-detail'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-fruit' => ['GET'],
            'get-group' => ['GET'],
            'get-feature' => ['GET'],
            'get-detail' => ['GET']
        ];
    }

    public function actionGetFruit()
    {
        $query = Fruit::find()->orderBy(['id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetGroup()
    {
        $query = \api\models\Group::find()->orderBy(['id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetFeature()
    {
        $query = Feature::find()->orderBy(['id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetDetail($fruit_id, $group_id, $feature_id = '')
    {
        if (!$fruit_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'fruit_id')]));
        }

        if (!$group_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'group_id')]));
        }
        $arr_feature = explode(',', $feature_id);
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;

        $detail = \api\models\Detail::find()
            ->andWhere([
                'and',
                ['fruit_id' => $fruit_id],
                (['IN', 'feature_id', $arr_feature]),
                ['group_id' => $group_id]
            ])
            ->orWhere([
                'and',
                ['fruit_id' => $fruit_id],
                ['group_id' => $group_id]
            ])
            ->orWhere([
                'and',
                ['fruit_id' => $fruit_id],
                (['IN', 'feature_id', $arr_feature])
            ])
            ->orWhere([
                'and',
                ['group_id' => $group_id],
                (['IN', 'feature_id', $arr_feature])
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $detail,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
        ]);

        return $dataProvider;

    }
}
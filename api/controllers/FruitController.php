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
use common\models\Detail;
use common\models\Feature;
use common\models\Group;
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
        $query = Group::find()->orderBy(['id' => SORT_ASC]);

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

    public function actionGetDetail($fruit_id, $group_id, $feature_id)
    {
        if (!$fruit_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'fruit_id')]));
        }

        if (!$group_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'group_id')]));
        }

        if (!$feature_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'feature_id')]));
        }

        $detail = \api\models\Detail::find()
            ->andWhere([
                'and',
                ['fruit_id' => $fruit_id],
                ['feature_id' => $feature_id],
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
                ['feature_id' => $feature_id]
            ])
            ->orWhere([
                'and',
                ['group_id' => $group_id],
                ['feature_id' => $feature_id]
            ])
            ->one();
        if (!$detail) {
            throw new InvalidValueException(Message::getNotFoundContentMessage());
        }
        return $detail;

    }
}
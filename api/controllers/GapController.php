<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 11:23 PM
 */

namespace api\controllers;


use api\helpers\Message;
use common\models\GapGeneral;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;

class GapController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'get-list-gap',
            'search',
            'detail-gap'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-gap'
        ];
    }

    public function actionGetListGap()
    {
        $page = isset($_GET['page']) && $_GET['page '] > 1 ? $_GET['page'] - 1 : 0;
        $query = GapGeneral::find()->andWhere(['status' => GapGeneral::STATUS_ACTIVE]);
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

    public function actionSearch($keyword = '')
    {
        $query = GapGeneral::find()->andWhere(['like', 'lower(gap)', strtolower($keyword)])
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE]);
        $defaultSort = ['created_at' => SORT_DESC];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => $defaultSort,
            ],
        ]);
        return $dataProvider;
    }

    public function actionDetailGap()
    {
        $id = $this->getParameter('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id')]));
        }
        $gap = GapGeneral::findOne([$id]);
        if ($gap) {
            return $gap;
        }
        $this->setStatusCode(500);
        return ['message' => 'Not found'];
    }
}
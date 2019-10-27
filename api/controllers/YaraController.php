<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
use api\models\Station;
use api\models\YaraSupplier;
use common\helpers\CVietnameseTools;
use common\models\SubscriberActivity;
use api\models\YaraGap;
use Yii;
use yii\base\InvalidValueException;
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
//            'get-list-station',
            'get-location',
            'get-detail-supplier',
            'search-gap'
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
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);
        return $dataProvider;

    }

    public function actionSearch($keyword = '')
    {
        UserHelpers::manualLogin();
        $query = \api\models\YaraSupplier::find()
            ->andWhere('longitude is not null')
            ->andWhere('latitude is not null')
            ->andFilterWhere(['or',
                ['like', 'name', $keyword],
                ['like', 'address', $keyword]
            ]);
        $defaultSort = ['name' => SORT_ASC];

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

    public function actionGetDetailSupplier($id)
    {
        $detail = YaraSupplier::find()
            ->andWhere(['id' => $id])->one();
        if ($detail) {
            return $detail;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGetListGap()
    {
        UserHelpers::manualLogin();
        $fruitId = $this->getParameter('fruit_id', '');
        if (!$fruitId) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'fruit_id')]));
        }
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = YaraGap::find()
            ->andWhere(['status' => YaraGap::STATUS_ACTIVE])
            ->andWhere(['fruit_id' => (int)$fruitId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['order' => SORT_DESC],
            ],
        ]);

        if ($query->one()) {
            return $dataProvider;
        } else {
            throw new ServerErrorHttpException("Danh mục này đang được cập nhật nội dung!");
        }
    }
    public function actionSearchGap($keyword = '')
    {
        $query = YaraGap::find()->andWhere(['like', 'lower(title)', strtolower($keyword)])
            ->orWhere(['like', 'lower(content)', strtolower($keyword)])
            ->andWhere(['status' => YaraGap::STATUS_ACTIVE]);
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
        $gap = \common\models\YaraGap::findOne([$id]);
        if ($gap) {
            return $gap;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }
}
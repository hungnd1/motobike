<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 11:23 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
use common\helpers\CUtils;
use common\models\GapGeneral;
use common\models\ReportBuySellSearch;
use common\models\SubscriberActivity;
use DateTime;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

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
            'search',
            'detail-gap',
            'get-statistic',
//            'get-list-gap'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-gap' => ['GET']
        ];
    }

    public function actionGetListGap($type = GapGeneral::GAP_GENERAL, $category_id = GapGeneral::PET)
    {
        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        $page = $this->getParameter('page', 0);
        $page = $page > 1 ? $page - 1 : 0;

        $query = GapGeneral::find()
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE])
            ->andWhere(['type' => $type]);
        if ($type == GapGeneral::GAP_GENERAL) {
            $query->andWhere(['category_id' => $category_id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['order' => SORT_DESC, 'created_at' => SORT_DESC],
            ],
        ]);
        if ($subscriber) {
            if ($type == GapGeneral::GAP_GENERAL) {
                $description = 'Nguoi dung vao sau benh';
                $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_GAP_DISEASE, $description);
            } else {
                $description = 'Nguoi dung vao biến đổi khí hậu';
                $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_CLIMATE_CHANGE, $description);
            }
        }
        return $dataProvider;

    }

    public function actionSearch($keyword = '')
    {
        $query = GapGeneral::find()->andWhere(['like', 'lower(gap)', strtolower($keyword)])
            ->andWhere(['type' => GapGeneral::GAP_GENERAL])
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE]);
        $defaultSort = ['order' => SORT_DESC, 'created_at' => SORT_DESC];

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
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGetStatistic($fromDate = '', $toDate = '', $province_id = '', $coffee_type = '')
    {

        if ($fromDate) {
            if (!CUtils::validateDate($fromDate)) {
                throw new InvalidValueException(Message::getNotDateMessage());
            }
        }
        if ($toDate) {
            if (!CUtils::validateDate($toDate)) {
                throw new InvalidValueException(Message::getNotDateMessage());
            }
        }
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;

        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-100 days')->format('d/m/Y');

        $fromDate = $fromDate ? $fromDate : $from_date_default;
        $toDate = $toDate ? $toDate : $to_date_default;
        $searchModel = new ReportBuySellSearch();
        $searchModel->from_date = $fromDate;
        $searchModel->to_date = $toDate;
        $searchModel->province_id = $province_id;
        $searchModel->type_coffee = $coffee_type;
//        $searchModel->search($searchModel);
        return $searchModel->search($searchModel, $page);
    }
}
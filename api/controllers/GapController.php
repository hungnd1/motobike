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
use common\models\GapGeneral;
use common\models\SubscriberActivity;
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
            'detail-gap'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-gap'=>['GET']
        ];
    }

    public function actionGetListGap()
    {
        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        $page = $this->getParameter('page',0);
        $page = $page > 1 ? $page - 1 : 0;

        $query = GapGeneral::find()->andWhere(['status' => GapGeneral::STATUS_ACTIVE])->andWhere(['type'=>GapGeneral::GAP_GENERAL]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['order' => SORT_DESC,'created_at' => SORT_DESC],
            ],
        ]);
        if($subscriber){
            $description = 'Nguoi dung vao sau benh';
            $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_GAP_DISEASE, $description);
        }
        return $dataProvider;

    }

    public function actionSearch($keyword = '')
    {
        $query = GapGeneral::find()->andWhere(['like', 'lower(gap)', strtolower($keyword)])
            ->andWhere(['type'=>GapGeneral::GAP_GENERAL])
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE]);
        $defaultSort = ['order' => SORT_DESC,'created_at' => SORT_DESC];

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
        throw new ServerErrorHttpException(Yii::t('app','Lỗi hệ thống, vui lòng thử lại sau'));
    }
}
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
use api\models\News;
use common\models\SubscriberActivity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class NewsController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
//            'get-list-news',
            'search',
            'detail-news'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-list-news'
        ];
    }

    public function actionGetListNews()
    {
        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        $id = $this->getParameter('id', '');
        $fruitId = $this->getParameter('fruit_id','');
        if (!$id && !$fruitId) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id va fruit_id')]));
        }
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
//        $query = News::find()->andWhere(['status' => News::STATUS_ACTIVE])->orderBy(['updated_at' => SORT_DESC]);
        if($id){
            $query = News::find()
                ->andWhere(['status' => News::STATUS_ACTIVE])
                ->andWhere(['category_id' => (int)$id]);
        }else{
            $query = News::find()
                ->andWhere(['status' => News::STATUS_ACTIVE])
                ->andWhere(['fruit_id' => (int)$fruitId]);
        }

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
        /** @var  $lastActivity SubscriberActivity */
        $lastActivity = SubscriberActivity::find()->andWhere(['action'=>SubscriberActivity::ACTION_GAP])->orderBy(['id'=>SORT_DESC])->one();
        if($lastActivity) {
            if (time() - $lastActivity->created_at >= 5 * 60) {
                $description = 'Nguoi dung vao gap';
                $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_GAP, $description);
            }
        }
        if($query->one()){
            return $dataProvider;
        }else{
            throw new ServerErrorHttpException("Danh mục này đang được cập nhật nội dung!");
        }

    }

    public function actionSearch($keyword = '')
    {
        $query = News::find()->andWhere(['like', 'lower(title)', strtolower($keyword)])
            ->orWhere(['like', 'lower(content)', strtolower($keyword)])
            ->andWhere(['status' => News::STATUS_ACTIVE]);
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

    public function actionDetailNews()
    {
        $id = $this->getParameter('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id')]));
        }
        $gap = \common\models\News::findOne([$id]);
        if ($gap) {
            return $gap;
        }
        throw new ServerErrorHttpException(Yii::t('app','Lỗi hệ thống, vui lòng thử lại sau'));
    }
}
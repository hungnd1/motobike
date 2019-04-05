<?php
/**
 * Created by PhpStorm.
 * User: HDN
 * Date: 4/23/2018
 * Time: 9:17 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
use api\models\Detail;
use api\models\Fruit;
use common\models\Feature;
use common\models\IsRating;
use common\models\Subscriber;
use common\models\SubscriberActivity;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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
            'get-feature',
            'get-list-detail',
            'get-detail'

//            'get-group'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-fruit' => ['GET'],
            'get-group' => ['GET'],
            'get-feature' => ['GET'],
            'get-list-detail' => ['GET'],
            'get-detail' => ['GET']
        ];
    }

    public function actionGetFruit($type = Fruit::CAPHE_VOI, $is_primary = 1)
    {
        if ($type == Fruit::CAPHE_VOI) {
            $query = Fruit::find()
                ->andWhere(['is_primary' => $is_primary])
                ->andWhere('parent_id is null')
                ->orderBy(['order' => SORT_ASC]);
        } else {
            $query = Fruit::find()
                ->andWhere(['is_primary' => $is_primary])
                ->andWhere('have_child is null')
                ->orderBy(['order' => SORT_ASC]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetGroup()
    {
        UserHelpers::manualLogin();

        $subscriber = Yii::$app->user->identity;
        /** @var  $subscriber Subscriber */

        $query = \api\models\Group::find()->orderBy(['id' => SORT_ASC]);
        $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_TRA_CUU_SU_CO, 'Tra cuu su co bat thuong');
        $isRating = IsRating::addIsRating(SubscriberActivity::ACTION_TRA_CUU_SU_CO, $subscriber->id);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetFeature()
    {
        $query = Feature::find()->andWhere(['status'=>Feature::STATUS_ACTIVE])->orderBy(['order' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function actionGetListDetail($fruit_id, $group_id, $feature_id = '')
    {
        if (!$fruit_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'fruit_id')]));
        }

        if (!$group_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'group_id')]));
        }
//        if (!$feature_id) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'feature_id')]));
//        }
        throw new ServerErrorHttpException(Yii::t('app', 'Nội dung này chúng tôi đang thực hiện, xin gửi thông tin đến các bạn sau'));

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

    public function actionGetDetail($id)
    {
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id')]));
        }
        $detail = Detail::findOne($id);
        if (!$detail) {
            throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
        }
        return $detail;

    }
}
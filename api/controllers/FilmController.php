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
use api\models\Subscriber;
use common\models\IsRating;
use common\models\RaFilmDocument;
use common\models\SubscriberActivity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class FilmController extends ApiController
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
            'get-list-film'
        ];
    }

    public function actionGetListFilm()
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        $fruitId = $this->getParameter('fruit_id', '');
        if (!$fruitId) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'fruit_id')]));
        }
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
//        $query = News::find()->andWhere(['status' => News::STATUS_ACTIVE])->orderBy(['updated_at' => SORT_DESC]);
        $query = \api\models\RaFilmDocument::find()
            ->andWhere(['status' => RaFilmDocument::STATUS_ACTIVE])
            ->andWhere(['fruit_id' => (int)$fruitId])
            ->orderBy(['id'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ]
        ]);
        if ($query->one()) {
            return $dataProvider;
        } else {
            throw new ServerErrorHttpException("Danh mục này đang được cập nhật nội dung!");
        }

    }

}
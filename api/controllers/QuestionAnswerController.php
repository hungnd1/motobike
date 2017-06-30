<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 11:23 AM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
use api\models\QuestionAnswer;
use common\models\User;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class QuestionAnswerController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'get-list-question-answer',
            'search',
            'detail-question'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'question-and-answer'=>['POST']
        ];
    }

    public function actionGetListQuestionAnswer()
    {
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = QuestionAnswer::find()->andWhere(['status' => QuestionAnswer::STATUS_ACTIVE]);
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
        $query = QuestionAnswer::find()->andWhere(['like', 'lower(question)', strtolower($keyword)])
            ->orWhere(['like', 'lower(answer)', strtolower($keyword)])
            ->andWhere(['status' => QuestionAnswer::STATUS_ACTIVE]);
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

    public function actionDetailQuestion()
    {
        $id = $this->getParameter('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id')]));
        }
        $question = QuestionAnswer::findOne([$id]);
        if ($question) {
            return $question;
        }
        $this->setStatusCode(500);
        return ['message' => 'Not found'];
    }

    public function actionQuestionAndAnswer(){

        UserHelpers::manualLogin();

        $question = $this->getParameterPost('question',null);
        $base = $this->getParameterPost('image','');
        $filename = "123.jpg";
        $binary=base64_decode($base);
        var_dump($binary);exit;
        $url = Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@question') . DIRECTORY_SEPARATOR);
        $file_name = Yii::$app->user->id . '.' . uniqid() . time().'.jpg';
        $tmp = Yii::getAlias('@api') . '/web/' . Yii::getAlias('@question') . '/';
        if (!file_exists($tmp)) {
            mkdir($tmp, 0777, true);
        }
//        move_uploaded_file($tmp,)
//        if ($image->saveAs($tmp . $file_name)) {
//            $model->image = $file_name;
//        }
        $file = fopen($url.$filename, 'w');
        fwrite($file, $binary);
        fclose($file);
        echo $filename;
//        $image = $this->getParameterPost('image','');
//        if($image){
//            $binary = base64_decode($image);
//            header('Content-Type: bitmap; charset=utf-8');
//            $file = fopen(Yii::$app->user->id . '.' . uniqid() . time() . '.jpg', 'wb');
//            fwrite($file, $binary);
//            fclose($file);
//        }

    }
}
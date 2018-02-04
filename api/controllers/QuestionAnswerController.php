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
use common\models\MatrixFertilizing;
use common\models\Subscriber;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

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
            'detail-question',
            'fertilizing',
            'get-feedback'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'question-and-answer' => ['POST'],
            'fertilizing' => ['POST']
        ];
    }

    public function actionGetListQuestionAnswer()
    {
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = QuestionAnswer::find();
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
            ->orWhere(['like', 'lower(answer)', strtolower($keyword)]);
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
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionQuestionAndAnswer()
    {

        UserHelpers::manualLogin();
        $question = $this->getParameterPost('question', null);
        $base = $this->getParameterPost('image', '');
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;

        if (!$question) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Câu hỏi')]));
        }
        $file_name = '';
        if ($base) {
            $binary = base64_decode($base, true);
            $url = Yii::getAlias('@question') . DIRECTORY_SEPARATOR;
            $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.jpg';
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
            file_put_contents($url . $file_name, $binary);
            $file = fopen($url . $file_name, 'wb');
            fwrite($file, $binary);
            fclose($file);
        }
        $question_answer = new \common\models\QuestionAnswer();
        $question_answer->question = $question;
        $question_answer->image = $file_name;
        $question_answer->created_at = time();
        $question_answer->subscriber_id = $subscriber->id;
        $question_answer->updated_at = time();
        $question_answer->status = QuestionAnswer::STATUS_INACTIVE;
        if ($question_answer->save(false)) {
            return [
                'message' => Yii::t('app', 'Bạn đã đặt câu hỏi thành công, hệ thống sẽ thông báo khi có câu trả lời'),
            ];
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionFertilizing()
    {
        $answer = $this->getParameterPost('answer', '');
        if (!$answer) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Câu trả lời')]));
        }
        $answer_1 = isset(explode(':', explode(',', $answer)[0])[1]) ? explode(':', explode(',', $answer)[0])[1] : 0;
        if (!$answer_1) {
            throw new InvalidValueException('Bạn phải trả lời hết các câu hỏi');
        }
        $answer_2 = isset(explode(',', $answer)[1]) ? explode(':', explode(',', $answer)[1])[1] : 0;
        if (!$answer_2) {
            throw new InvalidValueException('Bạn phải trả lời hết các câu hỏi');
        }
        $matrix = MatrixFertilizing::find()
            ->andWhere(['id_answer_1' => $answer_1])
            ->andWhere(['id_answer_2' => $answer_2])->one();
        if ($matrix) {
            return $matrix->content;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGetFeedback()
    {
        $arr = [];
        array_push($arr, array('id' => '1', 'content' => Yii::t('app', 'Mưa')));
        array_push($arr, array('id' => '2', 'content' => Yii::t('app', 'Mưa nhỏ')));
        array_push($arr, array('id' => '3', 'content' => Yii::t('app', 'Nắng')));
        array_push($arr, array('id' => '4', 'content' => Yii::t('app', 'Mưa to')));
        array_push($arr, array('id' => '5', 'content' => Yii::t('app', 'Mát ít mây')));
        array_push($arr, array('id' => '6', 'content' => Yii::t('app', 'Mát nhiều mây')));
        return [
            'title' => Yii::t('app', 'Để xác minh thời tiết tại vị trí của bạn. Vui lòng chọn từ miêu tả phù hợp với thời tiết hiện tại của bạn?'),
            'items' => $arr
        ];
    }
}
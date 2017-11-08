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
            'fertilizing'
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
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }

    public function actionQuestionAndAnswer()
    {

        UserHelpers::manualLogin();
        $question = $this->getParameterPost('question', null);
        $base = $this->getParameterPost('image', '');

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
        $question_answer->updated_at = time();
        $question_answer->status = QuestionAnswer::STATUS_INACTIVE;
        if ($question_answer->save(false)) {
            return [
                'message' => 'Bạn đã đặt câu hỏi thành công, hệ thống sẽ thông báo khi có câu trả lời',
            ];
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }

    public function actionFertilizing(){
        $answer = $this->getParameterPost('answer','');
        if (!$answer) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Câu trả lời')]));
        }
        $answer_1 = explode(':',explode(',',$answer)[0])[1];
        $answer_2 = explode(':',explode(',',$answer)[1])[1];
        $matrix = MatrixFertilizing::find()
            ->andWhere(['id_answer_1'=>$answer_1])
            ->andWhere(['id_answer_2'=>$answer_2])->one();
        if($matrix){
            return $matrix->content;
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }
}
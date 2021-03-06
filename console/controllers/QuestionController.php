<?php

/**
 * Swiss army knife to work with user and rbac in command line
 * @author: Nguyen Chi Thuc
 * @email: gthuc.nguyen@gmail.com
 */

namespace console\controllers;

use api\helpers\APIHelper;
use common\helpers\CUtils;
use common\helpers\FileUtils;
use common\models\DeviceInfo;
use Yii;
use yii\console\Controller;
use common\models\QuestionAnswer;

/**
 * UserController create user in commandline
 */
class QuestionController extends Controller
{
    public function actionAutoAnswer($id)
    {
        $this->infoLogAnswer("Start answer");
        $listQuestion = QuestionAnswer::find()
            ->andWhere(['status' => QuestionAnswer::STATUS_INACTIVE])
            ->andWhere(['id' => $id])
            ->andWhere('question is not null')
//            ->andWhere('image is null')
//            ->andWhere(['<=', 'created_at', time() - 5 * 3600])
            ->all();
        if ($listQuestion) {
            foreach ($listQuestion as $question) {
                /** @var $question QuestionAnswer */
                $data = '
                    {
                      "id": ' . $question->id . ',
                      "question": "' . $question->question . '"
                     }';
                $result = APIHelper::apiQuery('POST', APIHelper::API_ANSWER, $data);
                if (isset($result)) {
                    $answer = $this->getAnswerResult($result);
                    if ($answer) {
                        $question->answer = $answer;
                        $question->updated_at = time();
                        $question->status = QuestionAnswer::STATUS_ACTIVE;
                        $question->save(false);
                        /** @var  $device_token DeviceInfo */
                        $device_token = DeviceInfo::find()
                            ->innerJoin('device_subscriber_asm', 'device_subscriber_asm.device_id = device_info.id')
                            ->andWhere(['device_subscriber_asm.subscriber_id' => $question->subscriber_id])
                            ->one();
                        $clickAction = Yii::$app->params['action_android'];
                        CUtils::sendNotify($device_token->device_uid, "Bấm vào để xem chi tiết chuyên gia trả lời câu hỏi của bạn", "Hỏi đáp", $clickAction, DeviceInfo::TYPE_QUESTION, $id, DeviceInfo::TARGET_TYPE_QUESTION);
                    }
                }
            }
        }
    }

    private function getAnswerResult($result)
    {
        $chuoi = explode(',"', $result);
        $error_code = explode(':"', $chuoi[2]);
        $error_code = $error_code[1];

        return $error_code;
    }

    private function infoLogAnswer($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/infoAnswer.log'), $txt);
    }

    public function actionNotifyQuestion($id)
    {
        /** @var  $question  QuestionAnswer */
        $question = QuestionAnswer::find()
            ->andWhere(['id' => $id])
//            ->andWhere(['status' => QuestionAnswer::STATUS_INACTIVE])
            ->one();
        if ($question) {
            /** @var  $device_token DeviceInfo */
            $device_token = DeviceInfo::find()
                ->innerJoin('device_subscriber_asm', 'device_subscriber_asm.device_id = device_info.id')
                ->andWhere(['device_subscriber_asm.subscriber_id' => $question->subscriber_id])
                ->one();
            $clickAction = Yii::$app->params['action_android'];
            CUtils::sendNotify($device_token->device_uid, "Bấm vào để xem chi tiết chuyên gia trả lời câu hỏi của bạn", "Hỏi đáp", $clickAction, DeviceInfo::TYPE_QUESTION, $id, DeviceInfo::TARGET_TYPE_QUESTION);
            FileUtils::appendToFile(Yii::getAlias('@runtime/logs/notifyQuestion.log'), "Notify question " . $question->question . " voi id: " . $question->id);
        }
    }

    public function actionNotifyPest($id)
    {
        $device_token = DeviceInfo::find()
            ->innerJoin('device_subscriber_asm', 'device_subscriber_asm.device_id = device_info.id')
            ->all();
        $clickAction = Yii::$app->params['action_android'];
        foreach ($device_token as $token) {
            /** @var $token DeviceInfo */
            CUtils::sendNotify($token->device_uid, "Bấm vào để xem chi tiết thông tin sâu bệnh", "Sâu bệnh", $clickAction, DeviceInfo::TYPE_PETS, $id, DeviceInfo::TARGET_TYPE_PEST);
        }
    }
}

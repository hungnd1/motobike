<?php

/**
 * Swiss army knife to work with user and rbac in command line
 * @author: Nguyen Chi Thuc
 * @email: gthuc.nguyen@gmail.com
 */

namespace console\controllers;

use api\helpers\APIHelper;
use common\auth\helpers\AuthHelper;
use common\helpers\FileUtils;
use common\helpers\StringUtils;
use common\models\AuthItem;
use common\models\Site;
use common\models\User;
use ReflectionClass;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\rbac\DbManager;
use yii\rbac\Item;
use common\models\QuestionAnswer;

/**
 * UserController create user in commandline
 */
class QuestionController extends Controller
{
    public function actionAutoAnswer()
    {
        $this->infoLogAnswer("Start answer");
        $listQuestion = QuestionAnswer::find()
            ->andWhere(['status' => QuestionAnswer::STATUS_INACTIVE])
            ->andWhere('answer is null')
//            ->andWhere(['id' => $id])
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
                    }
                }
            }
        }
    }

    private function getAnswerResult($result)
    {
        $chuoi = explode(',', $result);
        $error_code = explode(':"', $chuoi[2]);
        $error_code = $error_code[1];
        return $error_code;
    }

    private function infoLogAnswer($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/infoAnswer.log'), $txt);
    }
}

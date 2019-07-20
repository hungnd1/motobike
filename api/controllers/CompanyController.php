<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use api\helpers\APIHelper;
use api\helpers\Message;
use api\helpers\UserHelpers;
use api\models\Detail;
use api\models\Exchange;
use api\models\ExchangeBuy;
use api\models\Service;
use common\helpers\CUtils;
use common\models\Company;
use common\models\Feedback;
use common\models\IsRating;
use common\models\PriceCoffee;
use common\models\Rating;
use common\models\SiteApiCredential;
use common\models\Subscriber;
use common\models\SubscriberActivity;
use common\models\SubscriberDictionary;
use common\models\SubscriberServiceAsm;
use common\models\SubscriberToken;
use common\models\SubscriberTransaction;
use DateTime;
use Madcoda\Youtube\Constants;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

class CompanyController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'login'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'login' => ['POST']
        ];
    }

    public function actionLogin()
    {
        $username = trim($this->getParameterPost('username', ''));
        $password = trim($this->getParameterPost('password', ''));
        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên tài khoản')]));
        }

        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }

        /** @var  $company Company*/
        $company = Company::find()->andWhere(['username'=>$username])->andWhere(['password'=>$password])->one();
        if(!$company){
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        return ['message' => Message::getLoginSuccessMessage(),
            'id' => $company->id
        ];
    }
}

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
use common\models\ExchangeFarmer;
use common\models\Subscriber;
use common\models\SubscriberToken;
use Yii;
use yii\base\InvalidValueException;
use yii\web\ServerErrorHttpException;

class SubscriberController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'login',
            'register'
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'get-user' => ['GET'],
            'login' => ['POST'],
            'register' => ['POST'],
            'get-info' => ['GET'],
            'change-info' => ['POST'],
            'exchange-coffee' => ['POST']
        ];
    }

    public function actionLogin()
    {
        $username = $this->getParameterPost('username', '');
        $password = $this->getParameterPost('password', '');

        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }

        $subscriber = Subscriber::findOne(['username' => $username]);

        if (!$subscriber->validatePassword($password)) {
            throw new InvalidValueException(Message::getWrongUserOrPassMessage());
        }

        $token = SubscriberToken::generateToken($subscriber->id, $subscriber->authen_type);
        if (!$token) {
            throw new ServerErrorHttpException(Message::getFailMessage());
        }

        $subscriber->last_login_at = time();
        $subscriber->save(false);

        return ['message' => Message::getLoginSuccessMessage(),
            'id' => $subscriber->id,
            'username' => $subscriber->username,
            'full_name' => $subscriber->full_name,
            'token' => $token->token,
            'expired_date' => $token->expired_at,
            'authen_type' => $subscriber->authen_type,
            'channel' => $token->channel,
        ];
    }

    public static function actionGetUser()
    {
        UserHelpers::manualLogin();
        return ['message' => '1'];

    }

    public function actionRegister()
    {
        $username = $this->getParameterPost('username', '');
        $password = $this->getParameterPost('password', '');
        $channel = $this->getParameterPost('channel', '');
        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }
        if (!$channel) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại tài khoản')]));
        }
        $subscriber = Subscriber::findOne(['username' => $username, 'status' => Subscriber::STATUS_ACTIVE]);
        if ($subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getExitsUsernameMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
        $subscriber = new Subscriber();
        $subscriber->username = $username;
        $subscriber->setPassword($password);
        $subscriber->verification_code = $password;
        $subscriber->status = Subscriber::STATUS_ACTIVE;
        $subscriber->created_at = time();
        $subscriber->updated_at = time();
        $subscriber->authen_type = $channel;
        if ($subscriber->save(false)) {
            return [
                'message' => 'Đăng ký tài khoản thành công, quý khách có thể đăng nhập hệ thống để sử dụng các ưu đãi',
            ];
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }

    public function actionChangeInfo()
    {
        UserHelpers::manualLogin();

        $fullname = $this->getParameterPost('fullname', '');
        $sex = $this->getParameterPost('sex', 1);
        $address = $this->getParameterPost('address', '');
        if (!$fullname) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Họ và tên')]));
        }

        if (!$address) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Địa chỉ')]));
        }
        $subscriber = Subscriber::findOne(['id' => Yii::$app->user->id]);
        $subscriber->full_name = $fullname;
        $subscriber->sex = $sex;
        $subscriber->address = $address;
        if ($subscriber->save(false)) {
            return true;
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');


    }

    public function actionGetInfo()
    {
        UserHelpers::manualLogin();
        $subscriber = Yii::$app->user->identity;
        if (!$subscriber) {
            throw new InvalidValueException(Message::getAccessDennyMessage());
        }

        return $subscriber;
    }

    public function actionExchangeCoffee()
    {
        UserHelpers::manualLogin();

        $quality = $this->getParameterPost('quality', '');
        $sold = $this->getParameterPost('sold', '');
        $desire_to_sell = $this->getParameterPost('desire_to_sell', '');
        $type_coffee = $this->getParameterPost('type_coffee', 1);
        $subscriber = Yii::$app->user->id;
        if (!$subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Người dùng chưa đăng nhập')]));
        }
        if (!$quality) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Sản lượng')]));
        }

        if (!$sold) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Sản lượng đã bán')]));
        }

        if (!$desire_to_sell) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Sản lượng mong muốn bán')]));
        }
        if (!$type_coffee) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại cafe')]));
        }


        $exchange = new ExchangeFarmer();
        $exchange->quanlity = $quality;
        $exchange->sold = $sold;
        $exchange->desire_to_sell = $desire_to_sell;
        $exchange->type_coffee = $type_coffee;
        $exchange->subscriber_id = Yii::$app->user->id;
        $exchange->type_coffee = $type_coffee;
        $exchange->created_at = time();
        $exchange->updated_at = time();
        if ($exchange->save()) {
            return true;
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }
}

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
            'register' => ['POST']
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
}
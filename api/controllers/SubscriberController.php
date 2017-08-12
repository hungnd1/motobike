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
use api\models\Exchange;
use api\models\ExchangeBuy;
use common\helpers\CUtils;
use common\models\Subscriber;
use common\models\SubscriberToken;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
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
            'register',
            'run',
            'reset-password'
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
            'reset-password' => ['POST'],
            'change-info' => ['POST'],
            'exchange-coffee' => ['POST'],
            'exchange-buy' => ['POST'],
            'change-password' => ['POST']
        ];
    }

    public function actionLogin()
    {
        $username = $this->getParameterPost('username', '');
//        $password = $this->getParameterPost('password', '');

        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
//        if (!$password) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
//        }
//        $phone_number = CUtils::validateMobile($username, 0);
//        if ($phone_number == '') {
//            $phone_number = CUtils::validateMobile($username, 1);
//            if ($phone_number == '') {
//                $phone_number = CUtils::validateMobile($username, 2);
//                if ($phone_number == '') {
//                    throw new InvalidValueException('Số điện thoại không đúng định dạng');
//                }
//            }
//        }

        $subscriber = Subscriber::findOne(['username' => $username]);
        $password = CUtils::generateRandomString(8);
        if (!$subscriber) {
            $subscriber = new Subscriber();
            $subscriber->username = $username;
            $subscriber->setPassword($password);
            $subscriber->msisdn = $password;
            $subscriber->verification_code = $password;
            $subscriber->status = Subscriber::STATUS_ACTIVE;
            $subscriber->created_at = time();
            $subscriber->updated_at = time();
            $subscriber->authen_type = Subscriber::AUTHEN_TYPE_NORMAL;
            $subscriber->save();
        }
//        if (!$subscriber->validatePassword($password)) {
//            throw new InvalidValueException(Message::getWrongUserOrPassMessage());
//        }

        $token = SubscriberToken::generateToken($subscriber->id, $subscriber->authen_type);
        if (!$token) {
            throw new ServerErrorHttpException(Message::getFailMessage());
        }

        $subscriber->last_login_at = time();
        $subscriber->save(false);

        return ['message' => Message::getLoginSuccessMessage(),
            'id' => $subscriber->id,
            'avatar' => $subscriber->getImageAvatar($subscriber->avatar_url),
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
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Số điện thoại')]));
        }
        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }
        if (!$channel) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại tài khoản')]));
        }
        $phone_number = CUtils::validateMobile($username, 0);
        if ($phone_number == '') {
            $phone_number = CUtils::validateMobile($username, 1);
            if ($phone_number == '') {
                $phone_number = CUtils::validateMobile($username, 2);
                if ($phone_number == '') {
                    throw new InvalidValueException('Số điện thoại không đúng định dạng');
                }
            }
        }
        $subscriber = Subscriber::findOne(['username' => $phone_number, 'status' => Subscriber::STATUS_ACTIVE]);
        if ($subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getExitsUsernameMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
        $subscriber = new Subscriber();
        $subscriber->username = $phone_number;
        $subscriber->setPassword($password);
        $subscriber->verification_code = $password;
        $subscriber->status = Subscriber::STATUS_ACTIVE;
        $subscriber->created_at = time();
        $subscriber->updated_at = time();
        $subscriber->authen_type = $channel;
        if ($subscriber->save(false)) {
            return [
                'message' => 'Đăng ký tài khoản thành công, quý khách có thể đăng nhập hệ thống để sử dụng các dịch vụ',
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
        $base = $this->getParameterPost('image', '');
        if (!$fullname) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Họ và tên')]));
        }
        if (!$address) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Địa chỉ')]));
        }
        $subscriber = Subscriber::findOne(['id' => Yii::$app->user->id]);
        $file_name = '';
        if ($base) {
            $binary = base64_decode($base, true);
            $url = Yii::getAlias('@avatar') . DIRECTORY_SEPARATOR;
            $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.jpg';
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
            file_put_contents($url . $file_name, $binary);
            $file = fopen($url . $file_name, 'wb');
            fwrite($file, $binary);
            fclose($file);
            $subscriber->avatar_url = $file_name;
        }

        $subscriber->full_name = $fullname;
        $subscriber->sex = $sex;
        $subscriber->address = $address;
        if ($subscriber->save(false)) {
            return ['message' => 'Cập nhật thông tin thành công!!!'];
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');


    }

    public function actionGetInfo()
    {
        UserHelpers::manualLogin();
        $subscriber = \api\models\Subscriber::findOne(Yii::$app->user->id);
        if (!$subscriber) {
            throw new InvalidValueException(Message::getAccessDennyMessage());
        }

        return $subscriber;
    }

    public function actionExchangeCoffee()
    {
        UserHelpers::manualLogin();

        $quality = $this->getParameterPost('total_quality_id', 0);
        $sold = $this->getParameterPost('sold_id', 0);
        $type_coffee = $this->getParameterPost('type_coffee', 0);
        $location = $this->getParameterPost('location', '');
        $location_name = $this->getParameterPost('location_name','');
        $price = $this->getParameterPost('price', 0);
        $subscriber = Yii::$app->user->id;
        if (!$subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Người dùng chưa đăng nhập')]));
        }
        if (!$quality) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tổng sản lượng')]));
        }

        if (!$sold) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Sản lượng  bán')]));
        }
        if (!$type_coffee) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại cafe')]));
        }
        if (!$price) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Giá')]));
        }


        $exchange = new Exchange();
        $exchange->total_quality_id = $quality;
        $exchange->sold_id = $sold;
        $exchange->type_coffee = $type_coffee;
        $exchange->location = $location;
        $exchange->location_name = $location_name;
        $exchange->subscriber_id = Yii::$app->user->id;
        $exchange->price = $price;
        $exchange->created_at = time();
        $exchange->updated_at = time();
        if ($exchange->save(false)) {
            return ['message' => 'Giao dịch của bạn đã được  đưa lên sàn, Xem lịch sử giao dịch để biết thêm chi tiết'];
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }

    public function actionTransactionSold()
    {

        UserHelpers::manualLogin();

        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = Exchange::find()->andWhere(['subscriber_id' => Yii::$app->user->id]);

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

    public function actionGetListExchangeSold()
    {

        UserHelpers::manualLogin();

        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = Exchange::find();

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

    public function actionGetListExchangeBuy()
    {

        UserHelpers::manualLogin();

        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = ExchangeBuy::find();

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

    public function actionExchangeBuy()
    {
        UserHelpers::manualLogin();

        $quality = $this->getParameterPost('total_quantity', 0);
        $type_coffee = $this->getParameterPost('type_coffee', 0);
        $location = $this->getParameterPost('location', '');
        $location_name = $this->getParameterPost('location_name','');
        $price = $this->getParameterPost('price', 0);
        $subscriber = Yii::$app->user->id;
        if (!$subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Người dùng chưa đăng nhập')]));
        }
        if (!$quality) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tổng sản lượng mua')]));
        }

        if (!$type_coffee) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại cafe')]));
        }
        if (!$price) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Giá')]));
        }


        $exchange = new ExchangeBuy();
        $exchange->total_quantity = $quality;
        $exchange->type_coffee_id = $type_coffee;
        $exchange->location = $location;
        $exchange->location_name = $location_name;
        $exchange->type_coffee_id = $type_coffee;
        $exchange->subscriber_id = Yii::$app->user->id;
        $exchange->price_buy = $price;
        $exchange->created_at = time();
        $exchange->updated_at = time();
        if ($exchange->save(false)) {
            return ['message' => 'Giao dịch của bạn đã được  đưa lên sàn, Xem lịch sử giao dịch để biết thêm chi tiết'];
        }
        throw new ServerErrorHttpException('Lỗi hệ thống, vui lòng thử lại sau');
    }

    public function actionTransactionBuy()
    {

        UserHelpers::manualLogin();

        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = ExchangeBuy::find()->andWhere(['subscriber_id' => Yii::$app->user->id]);

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

    public function actionChangePassword(){

        UserHelpers::manualLogin();

        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;

        $new_password = $this->getParameterPost('new_password', '');
        $old_password = $this->getParameterPost('old_password','');

        if (!$new_password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu cũ mới')]));
        }
        if (!$old_password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }
        if (!$subscriber) {
            throw new InvalidValueException(Message::getAccessDennyMessage());
        }

        if (!$subscriber->validatePassword($old_password)) {
            throw new InvalidValueException(Message::getChangeOldPassFailMessage());
        }
        $subscriber->password = $new_password;
        $subscriber->setPassword($new_password);

        if (!$subscriber->validate() || !$subscriber->save()) {
//            $message = $subscriber->getFirstMessageError();
            throw new InvalidValueException("error");
        }

        $st = SubscriberToken::findByAccessToken($subscriber->access_token);
        $st->status = SubscriberToken::STATUS_INACTIVE;
        if (!$st->save()) {
            throw new ServerErrorHttpException(Message::getFailMessage());
        }
        return ['message' => Message::getChangePassSuccessMessage()];

    }

    public function actionResetPassword(){

        $username = $this->getParameterPost('username', '');
        $new_password = $this->getParameterPost('new_password','');

        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
        if (!$new_password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }
        $phone_number = CUtils::validateMobile($username, 0);
        if ($phone_number == '') {
            $phone_number = CUtils::validateMobile($username, 1);
            if ($phone_number == '') {
                $phone_number = CUtils::validateMobile($username, 2);
                if ($phone_number == '') {
                    throw new InvalidValueException('Số điện thoại không đúng định dạng');
                }
            }
        }

        $subscriber = Subscriber::findOne(['username' => $phone_number]);
        if (!$subscriber) {
            throw new InvalidValueException('Thông tin tài khoản  không hợp lệ');
        }

        $subscriber->password = $new_password;
        $subscriber->setPassword($new_password);

        if (!$subscriber->validate() || !$subscriber->save()) {
//            $message = $subscriber->getFirstMessageError();
            throw new InvalidValueException("error");
        }

        $st = SubscriberToken::findByAccessToken($subscriber->access_token);
        $st->status = SubscriberToken::STATUS_INACTIVE;
        if (!$st->save()) {
            throw new ServerErrorHttpException(Message::getFailMessage());
        }
        return ['message' => Message::getChangePassSuccessMessage()];

    }

}

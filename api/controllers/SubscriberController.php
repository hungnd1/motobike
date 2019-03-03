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
use api\models\Detail;
use api\models\Exchange;
use api\models\ExchangeBuy;
use api\models\Service;
use common\helpers\CUtils;
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
            'reset-password',
            'transaction-buy',
            'transaction-sold',


            'get-list-exchange-sold',
            'get-list-exchange-buy',
            'get-list-dictionary',
            'question-upload',
            'is-rating'
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
            'change-password' => ['POST'],
            'feedback' => ['POST'],
            'register-package' => ['POST'],
            'rating' => ['POST'],
            'detail' => ['GET'],
            'question-upload'=>['POST'],
            'get-list-dictionary' => ['GET']
        ];
    }

    public function actionLogin()
    {
        $username = trim($this->getParameterPost('username', ''));
        $full_name = trim($this->getParameterPost('fullName', ''));
        $age = $this->getParameterPost('age', 0);
        $sex = $this->getParameterPost('gender', 0);
        $address = $this->getParameterPost('address', '');
//        if ($this->type == SiteApiCredential::TYPE_IOS_APPLICATION) {
//            $password = $this->getParameterPost('password', '');
//        }
        $request = Yii::$app->request;
        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
//        if ($this->type == SiteApiCredential::TYPE_IOS_APPLICATION) {
//            if (!$password) {
//                throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
//            }
//        }
//        if (!$password) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
//        }
//        if($this->type == SiteApiCredential::TYPE_ANDROID_APPLICATION){
//        $phone_number = CUtils::validateMobile($username, 0);
//        if ($phone_number == '') {
//            $phone_number = CUtils::validateMobile($username, 1);
//            if ($phone_number == '') {
//                $phone_number = CUtils::validateMobile($username, 2);
//                if ($phone_number == '') {
//                    throw new InvalidValueException(Yii::t('app', 'Số điện thoại không đúng định dạng'));
//                }
//            }
//        }
//        }
        /** @var  $subscriber  Subscriber */
        $subscriber = Subscriber::find()->andWhere(['username' => $username])
            ->andWhere(['status' => Subscriber::STATUS_ACTIVE])->orderBy(['id' => SORT_DESC])->one();

//        if ($this->type == SiteApiCredential::TYPE_IOS_APPLICATION) {
//            if (!$subscriber->validatePassword($password)) {
//                throw new InvalidValueException(Message::getWrongUserOrPassMessage());
//            }
//        }
        $password = CUtils::generateRandomString(8);
        if (!$subscriber) {
            $subscriber = new Subscriber();
            $subscriber->username = $username;
            $subscriber->full_name = $full_name;
            $subscriber->sex = $sex;
            $subscriber->address = $address;
            $subscriber->age = $age;
            $subscriber->setPassword($password);
            $subscriber->msisdn = $password;
            $subscriber->verification_code = $password;
            $subscriber->status = Subscriber::STATUS_ACTIVE;
            $subscriber->created_at = time();
            $subscriber->updated_at = time();
            $subscriber->authen_type = Subscriber::AUTHEN_TYPE_NORMAL;
            $subscriber->save();
        } else {
            if ($full_name) {
                $subscriber->full_name = $full_name;
            }
            if ($age) {
                $subscriber->age = $age;
            }
            if ($address) {
                $subscriber->address = $address;
            }
            if ($sex) {
                $subscriber->sex = $sex;
            }
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
        $description = 'Nguoi dung dang nhap';
        $subscriberActivity = SubscriberActivity::addActivity($subscriber, $request->getUserIP(), $this->type, SubscriberActivity::ACTION_LOGIN, $description);

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
//        $fullname = $this->getParameterPost('fullname', '');
        $username = $this->getParameterPost('username', '');
        $password = $this->getParameterPost('password', '');
        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên tài khoản')]));
        }
        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }

//        $phone_number = CUtils::validateMobile($username, 0);
//        if ($phone_number == '') {
//            $phone_number = CUtils::validateMobile($username, 1);
//            if ($phone_number == '') {
//                $phone_number = CUtils::validateMobile($username, 2);
//                if ($phone_number == '') {
//                    throw new InvalidValueException(Yii::t('app', 'Số điện thoại không đúng định dạng'));
//                }
//            }
//        }
        $subscriber = Subscriber::findOne(['username' => $username, 'status' => Subscriber::STATUS_ACTIVE]);
        if ($subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getExitsUsernameMessage(), [Yii::t('app', 'Tên đăng nhập')]));
        }
        $subscriber = new Subscriber();
        $subscriber->username = $username;
        $subscriber->setPassword($password);
        $subscriber->msisdn = $password;
        $subscriber->verification_code = $password;
        $subscriber->status = Subscriber::STATUS_ACTIVE;
        $subscriber->created_at = time();
//        $subscriber->full_name = $fullname;
        $subscriber->updated_at = time();
        $subscriber->authen_type = $this->type;
        if ($subscriber->save(false)) {
            return [
                'message' => Yii::t('app', 'Đăng ký tài khoản thành công, quý khách có thể đăng nhập hệ thống để sử dụng các dịch vụ'),
            ];
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionChangeInfo()
    {
        UserHelpers::manualLogin();

        $fullname = $this->getParameterPost('fullname', '');
        $sex = $this->getParameterPost('sex', 1);
        $address = $this->getParameterPost('address', '');
        $base = $this->getParameterPost('image', '');
        $age = $this->getParameterPost('age','');
        if (!$fullname) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Họ và tên')]));
        }
        if (!$address) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Địa chỉ')]));
        }
        if (!$age) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tuổi')]));
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
        $subscriber->age = $age;
        $subscriber->address = $address;
        $subscriber->updated_at = time();
        if ($subscriber->save(false)) {
            return ['message' => Yii::t('app', 'Cập nhật thông tin thành công!!!')];
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));


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

        $quality = $this->getParameterPost('total_quantity', 0);
        $province_id = $this->getParameterPost('province_id', 0);
        $type_coffee = $this->getParameterPost('type_coffee', 0);
        $location = $this->getParameterPost('location', '');
        $location_name = $this->getParameterPost('location_name', '');
        $price = $this->getParameterPost('price', 0);
        $subscriber = Yii::$app->user->id;
        if (!$subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Người dùng chưa đăng nhập')]));
        }
        if (!$quality) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tổng sản lượng')]));
        }

        if (!$province_id || $province_id <= 0) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tỉnh')]));
        }
        if (!$type_coffee || $type_coffee <= 0) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại cafe')]));
        }
        if (!$price) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Giá')]));
        }

        $today = strtotime('today midnight');
        $tomorrow = strtotime('tomorrow');

        $maxPrice = PriceCoffee::find()
            ->andWhere(['>=', 'price_coffee.created_at', $today])
            ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
            ->andWhere(['not in', 'price_coffee.organisation_name', ['dRCL', 'dACN']])
            ->max('price_average');
        $minPrice = PriceCoffee::find()
            ->andWhere(['>=', 'price_coffee.created_at', $today])
            ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
            ->andWhere(['not in', 'price_coffee.organisation_name', ['dRCL', 'dACN']])
            ->min('price_average');


        if (!$minPrice || !$maxPrice || $price >= $minPrice && $price <= $maxPrice) {
            $exchange = new Exchange();
            $exchange->total_quantity = $quality;
            $exchange->province_id = $province_id;
            $exchange->type_coffee = $type_coffee;
            $exchange->location = $location;
            $exchange->location_name = $location_name;
            $exchange->subscriber_id = Yii::$app->user->id;
            $exchange->price = $price;
            $exchange->created_at = time();
            $exchange->updated_at = time();
            if ($exchange->save(false)) {
                return ['message' => Yii::t('app', 'Giao dịch của bạn đã được  đưa lên sàn, Xem lịch sử giao dịch để biết thêm chi tiết')];
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Giá nhập vào không được quá giá cao nhất và thấp nhất của ngày hôm nay'));
    }

    public function actionTransactionSold()
    {
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-12 days')->format('d/m/Y');
        $query = Exchange::find()
            ->andWhere('total_quantity is not null ')
            ->andWhere(['>=', 'created_at', $from_date_default]);

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

//        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
//        $subscriber = Yii::$app->user->identity;
//        $description = 'Nguoi dung vao danh sach mua';
//        $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_SELL, $description);
//        $isRating = IsRating::addIsRating(SubscriberActivity::ACTION_SELL, $subscriber->id);

        $timeExpired = time() - Yii::$app->params['timeExpired'] * 24 * 60 * 60;
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = Exchange::find()->andWhere(['>=', 'created_at', $timeExpired]);

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

//        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
//        $subscriber = Yii::$app->user->identity;
//        $description = 'Nguoi dung vao danh sach ban';
//        $subscriberActivity = SubscriberActivity::addActivity($subscriber, Yii::$app->request->getUserIP(), $this->type, SubscriberActivity::ACTION_BUY, $description);
//        $isRating = IsRating::addIsRating(SubscriberActivity::ACTION_BUY, $subscriber->id);
        $timeExpired = time() - Yii::$app->params['timeExpired'] * 24 * 60 * 60;

        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = ExchangeBuy::find()
            ->andWhere(['>=', 'created_at', $timeExpired]);

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
        $location_name = $this->getParameterPost('location_name', '');
        $price = $this->getParameterPost('price', 0);
        $province_id = $this->getParameterPost('province_id', 0);
        $subscriber = Yii::$app->user->id;
        if (!$subscriber) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Người dùng chưa đăng nhập')]));
        }
        if (!$quality) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tổng sản lượng mua')]));
        }
        if (!$province_id || $province_id <= 0) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tỉnh')]));
        }

        if (!$type_coffee || $type_coffee <= 0) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Loại cafe')]));
        }
        if (!$price) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Giá')]));
        }
        $today = strtotime('today midnight');
        $tomorrow = strtotime('tomorrow');

        $maxPrice = PriceCoffee::find()
            ->andWhere(['>=', 'price_coffee.created_at', $today])
            ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
            ->andWhere(['not in', 'price_coffee.organisation_name', ['dRCL', 'dACN']])
            ->max('price_average');
        if (!$maxPrice) {
            $maxPrice = PriceCoffee::find()
                ->andWhere(['not in', 'price_coffee.organisation_name', ['dRCL', 'dACN']])
                ->orderBy(['id' => SORT_DESC])
                ->max('price_average');
        }


        $minPrice = PriceCoffee::find()
            ->andWhere(['>=', 'price_coffee.created_at', $today])
            ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
            ->andWhere(['not in', 'price_coffee.organisation_name', ['dRCL', 'dACN']])
            ->min('price_average');

        if (!$minPrice || !$maxPrice || $price >= $minPrice && $price <= $maxPrice) {
            $exchange = new ExchangeBuy();
            $exchange->total_quantity = $quality;
            $exchange->type_coffee_id = $type_coffee;
            $exchange->location = $location;
            $exchange->location_name = $location_name;
            $exchange->type_coffee_id = $type_coffee;
            $exchange->subscriber_id = Yii::$app->user->id;
            $exchange->province_id = $province_id;
            $exchange->price_buy = $price;
            $exchange->created_at = time();
            $exchange->updated_at = time();
            if ($exchange->save(false)) {
                return ['message' => Yii::t('app', 'Giao dịch của bạn đã được  đưa lên sàn, Xem lịch sử giao dịch để biết thêm chi tiết')];
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Giá nhập vào không được quá giá cao nhất và thấp nhất của ngày hôm nay'));
    }

    public function actionTransactionBuy()
    {
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-12 days')->format('d/m/Y');
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = ExchangeBuy::find()
            ->andWhere('total_quantity is not null ')
            ->andWhere(['>=', 'created_at', $from_date_default]);

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

    public function actionChangePassword()
    {

        UserHelpers::manualLogin();

        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;

        $new_password = $this->getParameterPost('new_password', '');
        $old_password = $this->getParameterPost('old_password', '');

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

    public function actionResetPassword()
    {

        $username = $this->getParameterPost('username', '');
        $new_password = $this->getParameterPost('new_password', '');

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
                    throw new InvalidValueException(Yii::t('app', 'Số điện thoại không đúng định dạng'));
                }
            }
        }

        $subscriber = Subscriber::findOne(['username' => $phone_number]);
        if (!$subscriber) {
            throw new InvalidValueException(Yii::t('app', 'Thông tin tài khoản  không hợp lệ'));
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

    public function actionFeedback()
    {

        UserHelpers::manualLogin();

        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        $question_id = $this->getParameterPost('question_id', '');
        $station_id = $this->getParameterPost('station_id', '');
        if (!$question_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'question_id')]));
        }

        if (!$station_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'station_id')]));
        }

        $feedback = new Feedback();
        $feedback->user_id = $subscriber->id;
        $feedback->id_question = $question_id;
        $feedback->created_at = time();
        $feedback->station_id = $station_id;
        $feedback->save(false);
        return [
            'success' => true,
            'message' => Yii::t('app', 'Phản hồi thành công')
        ];
    }

    public function actionRegisterPackage()
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        $package_id = $this->getParameterPost('package_id', '');
        if (!$package_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'package id')]));
        }
        /** @var  $service Service */
        $service = Service::find()->andWhere(['status' => Service::STATUS_ACTIVE])->one();
        if (!$service) {
            throw new InvalidValueException($this->replaceParam(Message::getNotFoundServiceMessage()));
        }
        if ($subscriber->coin < $service->price) {
            throw new InvalidValueException('Bạn không đủ coin để mua gói cước này. Vui lòng nạp thêm coin vào tài khoản');
        }
        /** @var  $subscriberServiceAsm  SubscriberServiceAsm */
        $subscriberServiceAsm = SubscriberServiceAsm::find()
            ->andWhere(['service_id' => $service->id])
            ->andWhere(['subscriber_id' => $subscriber->id])
            ->andWhere(['status' => SubscriberServiceAsm::STATUS_ACTIVE])
            ->orderBy(['updated_at' => SORT_DESC])->one();
        //gia han goi
        if ($subscriberServiceAsm && $subscriberServiceAsm->time_expired - time() > 0) {
            $subscriberServiceAsm->time_expired = $subscriberServiceAsm->time_expired + $service->time_expired * 24 * 3600;
            $subscriberServiceAsm->updated_at = time();
            if ($subscriberServiceAsm->save()) {
                $subscriber->coin = $subscriber->coin - $service->price;
                $subscriber->save();
                //luu log transaction
                $subscriber->newTransaction($subscriber->id, SubscriberTransaction::TYPE_RENEW,
                    $service->id, SubscriberTransaction::STATUS_SUCCESS,
                    $service->price, 'Gia han goi cuoc', '0', $subscriberServiceAsm->id, $subscriberServiceAsm->time_expired);
                return [
                    'success' => true,
                    'message' => Yii::t('app', 'Gia hạn gói thành công')
                ];
            }
        } else {
            //add vao bang mua goi
            $subscriberServiceAsm = new SubscriberServiceAsm();
            $subscriberServiceAsm->service_id = $service->id;
            $subscriberServiceAsm->subscriber_id = $subscriber->id;
            $subscriberServiceAsm->time_expired = time() + $service->time_expired * 24 * 3600;
            $subscriberServiceAsm->status = SubscriberServiceAsm::STATUS_ACTIVE;
            $subscriberServiceAsm->created_at = time();
            $subscriberServiceAsm->updated_at = time();
            if ($subscriberServiceAsm->save()) {
                $subscriber->coin = $subscriber->coin - $service->price;
                $subscriber->save();
                //luu log transaction
                $subscriber->newTransaction($subscriber->id, SubscriberTransaction::TYPE_REGISTER,
                    $service->id, SubscriberTransaction::STATUS_SUCCESS,
                    $service->price, 'Mua goi cuoc', '0', $subscriberServiceAsm->id, $subscriberServiceAsm->time_expired);
                return [
                    'success' => true,
                    'message' => Yii::t('app', 'Mua gói thành công')
                ];
            }
        }

        return [
            'success' => false,
            'message' => Yii::t('app', 'Mua gói thất bại')
        ];
    }

    public function actionRating()
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        $rate = $this->getParameterPost('rate', 0);
        $content = $this->getParameterPost('content', '');
        $type = $this->getParameterPost('type', SubscriberActivity::ACTION_WEATHER);
        $rating = new Rating();
        $rating->rate = $rate;
        $rating->content = $content;
        $rating->created_at = time();
        $rating->updated_at = time();
        $rating->subscriber_id = $subscriber->id;
        $rating->type = $type;
        if ($rating->save()) {
            /** @var  $isRating IsRating */
            $isRating = IsRating::find()
                ->andWhere(['subscriber_id' => $subscriber->id])
                ->andWhere(['type' => $type])
                ->one();
            if ($isRating) {
                $isRating->status = Subscriber::STATUS_ACTIVE;
                $isRating->created_at = time();
                $isRating->save();
            }
            return [
                'success' => true,
                'message' => Yii::t('app', 'Cám ơn bạn đã đánh giá nội dung này')
            ];
        }
        throw new ServerErrorHttpException("Hệ thống đang lỗi");
    }

    public function actionIsRating($type = SubscriberActivity::ACTION_WEATHER)
    {
        throw new ServerErrorHttpException("Hệ thống đang lỗi");
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        /** @var  $rating Rating */
        /** @var  $isRating IsRating */
        $isRating = IsRating::find()
            ->andWhere(['subscriber_id' => $subscriber->id])
            ->andWhere(['type' => $type])
            ->one();
        if ($isRating) {
            if ($isRating->status == Subscriber::STATUS_INACTIVE) {

                $rating = Rating::find()
                    ->andWhere(['subscriber_id' => $subscriber->id])
                    ->andWhere(['type' => $type])
                    ->one();
                if ($rating) {
                    if (time() - $rating->created_at >= 30 * 24 * 3600) {
                        return [
                            'success' => true,
                            'message' => Yii::t('app', 'Cám ơn bạn đã đánh giá nội dung này')
                        ];
                        throw new ServerErrorHttpException("Hệ thống đang lỗi");
                    } else {
                        throw new ServerErrorHttpException("Hệ thống đang lỗi");
                    }
                } else {
                    throw new ServerErrorHttpException("Hệ thống đang lỗi");
                    return [
                        'success' => true,
                        'message' => Yii::t('app', 'Cám ơn bạn đã đánh giá nội dung này')
                    ];
                }
            } else {
                throw new ServerErrorHttpException("Hệ thống đang lỗi");
            }
        } else {
            throw new ServerErrorHttpException("Hệ thống đang lỗi");
        }
    }

    public function actionDetail($type = Subscriber::EXCHANGE_SALE, $id)
    {
//        UserHelpers::manualLogin();
        if ($type == Subscriber::EXCHANGE_SALE) {
            $exchange = Exchange::findOne($id);
        } else {
            $exchange = ExchangeBuy::findOne($id);
        }
        if (!$exchange) {
            throw new ServerErrorHttpException("Hệ thống đang lỗi");
        }
        return $exchange;
    }

    public function actionQuestionUpload()
    {

//        UserHelpers::manualLogin();
        $question = $this->getParameterPost('question', null);
        $base = $this->getParameterPost('image', '');
        $group_id = $this->getParameterPost('group_id',0);
        $fruit_id = $this->getParameterPost('fruit_id',0);
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;

        if (!$question) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Câu hỏi')]));
        }
        if (!$group_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'group_id')]));
        }
        if (!$fruit_id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'fruit_id')]));
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
        $questionUpload = new SubscriberDictionary();
        $questionUpload->image = $file_name;
//        $questionUpload->subscriber = $subscriber->id;
        $questionUpload->created_at = time();
        $questionUpload->group_id = $group_id;
        $questionUpload->content = $question;
        if ($questionUpload->save(false)) {
            return [
                'message' => Yii::t('app', 'Bạn đã đặt câu hỏi thành công, hệ thống sẽ thông báo khi có câu trả lời'),
            ];
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGetListDictionary($group_id, $fruit_id){
//        UserHelpers::manualLogin();
        $query = Detail::find()
            ->andWhere(['status'=>Detail::STATUS_ACTIVE])
            ->andWhere(['group_id'=>$group_id])
            ->andWhere(['fruit_id'=>$fruit_id]);
//            ->orderBy(new Expression("rand()"))->limit(9);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $dataProvider;
    }
}

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
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'upload-company' => ['POST']
        ];
    }

    public function actionLogin()
    {
        UserHelpers::manualLogin();
        $username = trim($this->getParameterPost('username', ''));
        $password = trim($this->getParameterPost('password', ''));
        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên tài khoản')]));
        }

        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }

        /** @var  $company Company */
        $company = Company::find()->andWhere(['username' => $username])->andWhere(['password' => $password])->one();
        if (!$company) {
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        return ['message' => Message::getLoginSuccessMessage(),
            'id' => $company->id
        ];
    }

    public function actionUploadCompany()
    {
        UserHelpers::manualLogin();

        $id = $this->getParameterPost('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id công ty')]));
        }
        $fileBase64 = $this->getParameterPost('file', '');
        $congTy = $this->getParameterPost('ten', '');
        $diachi = $this->getParameterPost('dia_chi', '');
        $kinhdoanh = $this->getParameterPost('kinh_doanh', '');
        $sanpham = $this->getParameterPost('san_pham', '');
        $dientich = $this->getParameterPost('dien_tich', '');
        $website = $this->getParameterPost('website', '');
        $extension = $this->getParameterPost('extension', '');
        /** @var  $company Company */
        $company = Company::find()->andWhere(['id'=>$id])->one();
        if(!$company){
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        $arr = array(
            'ten' => $congTy,
            'diachi' => $diachi,
            'kinhdoanh' => $kinhdoanh,
            'sanpham' => $sanpham,
            'dientich' => $dientich,
            'website' => $website
        );
        $company->description = json_encode($arr);
        if ($fileBase64) {
            $binary = base64_decode($fileBase64, true);
            $url = Yii::getAlias('@company') . DIRECTORY_SEPARATOR;
            $file_name = Yii::$app->user->id . '.' . uniqid() . time() . $extension;
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
            file_put_contents($url . $file_name, $binary);
            $file = fopen($url . $file_name, 'wb');
            fwrite($file, $binary);
            fclose($file);
            $company->file = $file_name;
        }
        if(!$company->save()){
            throw new ServerErrorHttpException("Lưu thông tin công ty thất bại");
        }
        return ['message' => "Lưu thông tin thành công",
            'id' => $company->id
        ];
    }

    public function actionGetCompany($id){
        UserHelpers::manualLogin();
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id công ty')]));
        }
        $company = \api\models\Company::find()->andWhere(['id'=>$id])->one();
        if(!$company){
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        return $company;
    }
}

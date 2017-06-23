<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "subscriber".
 *
 * @property integer $id
 * @property integer $authen_type
 * @property string $msisdn
 * @property string $username
 * @property integer $status
 * @property string $email
 * @property string $full_name
 * @property string $password
 * @property integer $last_login_at
 * @property integer $last_login_session
 * @property integer $birthday
 * @property integer $sex
 * @property string $avatar_url
 * @property string $skype_id
 * @property string $google_id
 * @property string $facebook_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $client_type
 * @property integer $using_promotion
 * @property string $verification_code
 * @property string $address
 * @property string $user_agent
 */
class Subscriber extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber';
    }
    public $access_token;

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const AUTHEN_TYPE_NORMAL = 1; //login thuong
    const AUTHEN_TYPE_FACE = 2; //login bang face

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authen_type', 'status', 'last_login_at', 'last_login_session', 'birthday', 'sex', 'created_at', 'updated_at', 'client_type', 'using_promotion'], 'integer'],
            [['msisdn'], 'required'],
            [['msisdn'], 'string', 'max' => 45],
            [['username', 'email'], 'string', 'max' => 100],
            [['full_name', 'password'], 'string', 'max' => 200],
            [['avatar_url', 'skype_id', 'google_id', 'facebook_id'], 'string', 'max' => 255],
            [['verification_code'], 'string', 'max' => 32],
            [['user_agent','address'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authen_type' => 'Loại tài khoản',
            'msisdn' => 'Msisdn',
            'username' => 'Tên đăng nhập',
            'status' => 'Trạng thái',
            'email' => 'Email',
            'full_name' => 'Full Name',
            'password' => 'Mật khẩu',
            'last_login_at' => 'Last Login At',
            'last_login_session' => 'Last Login Session',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'avatar_url' => 'Avatar Url',
            'skype_id' => 'Skype ID',
            'google_id' => 'Google ID',
            'facebook_id' => 'Facebook ID',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'client_type' => 'Client Type',
            'using_promotion' => 'Using Promotion',
            'verification_code' => 'Verification Code',
            'user_agent' => 'User Agent',
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => \Yii::t('app', 'Kích hoạt'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Tạm dừng'),
        ];
        return $lst;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    public static $device_type = [
        self::AUTHEN_TYPE_NORMAL => "Tài khoản thường",
        self::AUTHEN_TYPE_FACE => "Tài khoản face"
    ];

    public static function getListType()
    {
        return
            $credential_status = [
                self::AUTHEN_TYPE_NORMAL => "Tài khoản thường",
                self::AUTHEN_TYPE_FACE => "Tài khoản face"
            ];
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public static function getListStatusNameByType($status){
        $lst = self::getListType();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }

    public static function getListStatusNameByStatus($status){
        $lst = self::listStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        /* @var SubscriberToken $subscriber_token */
        /* @var Subscriber $subscriber */
        $subscriber_token = SubscriberToken::findByAccessToken($token);

        if ($subscriber_token) {
            $subscriber = $subscriber_token->getSubscriber()->one();
            if ($subscriber) {
                $subscriber->access_token = $token;
            }

            return $subscriber;
        }

        return null;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
//        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->getAuthKey() === $authKey;
    }

    public function getImageLink()
    {
        return $this->avatar_url ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@avatar_image') . DIRECTORY_SEPARATOR . $this->avatar_url, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

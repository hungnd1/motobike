<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "shopbike".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $password_hash
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property integer $like_count
 * @property integer $rating_count
 * @property string $facebook_id
 * @property string $avatar
 * @property integer $status
 * @property integer $created_at
 * @property integer $approved_at
 * @property integer $updated_at
 * @property integer $time_open
 * @property integer $time_close
 */
class Shopbike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shopbike';
    }

    const STATUS_ACTIVE = 10; // Đã duyệt
    const STATUS_INACTIVE = 0; // khóa
    const STATUS_DELETE = 2; // Xóa
    const STATUS_PENDING = 3; // CHỜ DUYỆT
    const MAX_SIZE_UPLOAD = 10485760; // 10 * 1024 * 1024

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'phone', 'status','time_open','time_close','address'], 'required','on' => 'adminModify', 'message' => '{attribute} không được để trống'],
            [['username'], 'unique','on' => 'adminModify', 'message' => 'Tài khoản đã tồn tại'],
            [['like_count', 'rating_count', 'status', 'created_at','approved_at', 'updated_at', 'time_open', 'time_close'], 'integer'],
            [['avatar'], 'string'],
            [['email'],'email'],
            [['username', 'password_hash', 'email', 'phone'], 'string', 'max' => 255],
            [['password', 'address', 'facebook_id'], 'string', 'max' => 500],
            [['avatar'],
                'file',
                'tooBig' => Yii::t('app', '{attribute} vượt quá dung lượng cho phép. Vui lòng thử lại'),
                'wrongExtension' => Yii::t('app', '{attribute} không đúng định dạng'),
                'extensions' => 'png, jpg, jpeg, gif',
                'maxSize' => self::MAX_SIZE_UPLOAD],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Tên tài khoản',
            'password' => 'Mật khẩu',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'like_count' => 'Like Count',
            'rating_count' => 'Rating Count',
            'facebook_id' => 'Facebook ID',
            'avatar' => 'Ảnh đại diện',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'approved_at' => 'Ngày kích hoạt',
            'updated_at' => 'Ngày cập nhật',
            'time_open' => 'Thời gian mở cửa',
            'time_close' => 'Thời gian đóng cửa',
        ];
    }

    public function getFirstImageLink()
    {
        // var_dump(Url::base());die;
        $link = '';
        if (!$this->avatar) {
            return;
        }
        $link = Url::to(Url::base() . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR . $this->avatar, true);

        return $link;
    }

    public static function getListStatus($type = 'all')
    {
        return ['all' => [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_PENDING => 'Chờ duyệt',
            self::STATUS_INACTIVE => 'Khóa',
        ],
            'filter' => [
                self::STATUS_ACTIVE => 'Hoạt động',
                self::STATUS_PENDING => 'Chờ duyệt',
                self::STATUS_INACTIVE => 'Khóa',
            ],
        ][$type];
    }
    public function spUpdateStatus($newStatus)
    {
        $this->status = $newStatus;
        if($newStatus == Shopbike::STATUS_ACTIVE){
            $this->approved_at = time();
        }
        $this->updated_at = time();
        return $this->update(false);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    public function getStatusName()
    {
        $listStatus = self::getListStatus();
        if (isset($listStatus[$this->status])) {
            return $listStatus[$this->status];
        }
        return '';
    }

}

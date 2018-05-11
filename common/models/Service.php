<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "service".
 *
 * @property integer $id
 * @property string $service_name
 * @property string $description
 * @property integer $time_expired
 * @property integer $status
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $price
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_name','time_expired','description','status','price','image'],'required'],
            [['description'], 'string'],
            [['status', 'created_at', 'updated_at', 'price','time_expired'], 'integer'],
            [['service_name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_name' => 'Tên gói cước',
            'description' => 'Mô tả',
            'time_expired' => 'Thời gian hết hạn',
            'status' => 'Trạng thái',
            'image' => 'Ảnh đại diện',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'price' => 'Giá',
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

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }

    public static function getListStatusNameByStatus($status){
        $lst = self::listStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }
}

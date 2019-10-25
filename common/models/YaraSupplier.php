<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "yara_supplier".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $image
 * @property string $content
 * @property integer $status
 * @property string $longitude
 * @property string $latitude
 * @property string $description
 */
class YaraSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yara_supplier';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content','name','longitude','latitude','status','address'], 'required'],
            [['content','description'], 'string'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['address', 'image'], 'string', 'max' => 500],
            [['longitude', 'latitude'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên đại lý',
            'address' => 'Địa chỉ',
            'image' => 'Hình ảnh',
            'content' => 'Nội dung chi tiết',
            'status' => 'Trạng thái',
            'longitude' => 'Kinh độ',
            'latitude' => 'Vĩ độ',
            'description' => 'Mô tả'
        ];
    }
    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
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
}

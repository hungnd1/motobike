<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "unit_link".
 *
 * @property integer $id
 * @property string $link
 * @property string $image
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name
 */
class UnitLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_link';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['link', 'image'], 'string', 'max' => 500],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Địa chỉ',
            'image' => 'Ảnh đại diện',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày kích hoạt',
            'updated_at' => 'Updated At',
            'name' => 'Tên đơn vị',
        ];
    }
    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => \Yii::t('app', 'Kích hoạt'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Tạm dừng'),
        ];
        return $lst;
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@unit_link') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

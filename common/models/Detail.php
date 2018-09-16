<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "detail".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $description
 * @property string $reason
 * @property string $harm
 * @property string $prevention
 * @property integer $feature_id
 * @property integer $group_id
 * @property integer $fruit_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $image
 * @property integer $status
 */
class Detail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detail';
    }


    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'reason', 'harm', 'prevention'], 'string'],
            [['image'], 'required', 'on' => 'admin_create_update'],
            [['feature_id', 'group_id', 'fruit_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['display_name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 500],
            [['display_name', 'reason', 'harm', 'prevention', 'description', 'feature_id', 'group_id', 'fruit_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Tên hiển thị',
            'description' => 'Mô tả',
            'reason' => 'Nguyên nhân',
            'harm' => 'Tác hại',
            'prevention' => 'Phòng/ trị',
            'feature_id' => 'Đặc điểm cây',
            'group_id' => 'Nhóm đặc điểm',
            'fruit_id' => 'Cây trồng',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'image' => 'Ảnh đại diện',
            'status' => 'Trạng thái',
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
}

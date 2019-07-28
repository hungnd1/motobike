<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "company_news".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $image
 * @property integer $company_id
 */
class CompanyNews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_description','content','company_id'], 'required'],
            [['image'], 'required', 'on' => 'admin_create_update'],
            [['short_description', 'content'], 'string'],
            [['created_at', 'updated_at', 'status', 'company_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'short_description' => 'Mô tả ngắn',
            'content' => 'Nội dung',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
            'image' => 'Ảnh đại diện',
            'company_id' => 'Công ty',
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

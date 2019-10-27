<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "yara_gap".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $image
 * @property integer $order
 * @property integer $fruit_id
 */
class YaraGap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yara_gap';
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content','fruit_id','status','image'],'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'status', 'order', 'fruit_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['short_description', 'image'], 'string', 'max' => 500],
        ];
    }

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
            'order' => 'Sắp xếp',
            'fruit_id' => 'Cây trồng',
        ];
    }

    public static function getFruits()
    {
        $arrFruit = [];
        $listFruit = Fruit::find()->all();
        foreach ($listFruit as $item) {
            /** @var $item Fruit */
            $arrFruit[$item->id] = $item->name;
        }
        return $arrFruit;
    }

    public function getFruitName($fruit_id)
    {
        $lst = self::getFruits();
        if (array_key_exists($fruit_id, $lst)) {
            return $lst[$fruit_id];
        }
        return $fruit_id;
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }

    public static function getListStatus()
    {
        return
            $credential_status = [
                self::STATUS_ACTIVE => Yii::t('app', 'Hoạt động'),
                self::STATUS_INACTIVE => Yii::t('app', 'Tạm dừng'),
            ];
    }

    public static function getListStatusFilter($type = 'all')
    {
        return ['all' => [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Khóa',
        ],
            'filter' => [
                self::STATUS_ACTIVE => 'Hoạt động',
                self::STATUS_INACTIVE => 'Khóa',
            ],
        ][$type];
    }
    public function getStatusName()
    {
        $lst = self::getListStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

}

<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $description
 * @property integer $status
 * @property integer $order_number
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $fruit_id
 * @property string $image
 * @property integer $type
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    //danh muc tai lieu ky thuat canh tac
    const TYPE_GAP_GOOD = 1;
    const TYPE_QA = 2;
    const TYPE_GAME = 3;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name','order_number'], 'required'],
            [['description', 'image'], 'string'],
            [['status', 'order_number', 'created_at', 'updated_at', 'fruit_id', 'type'], 'integer'],
            [['display_name'], 'string', 'max' => 200],
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
            'status' => 'Trạng thái',
            'order_number' => 'Sắp xếp',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
            'fruit_id' => 'Cây trồng',
            'image' => 'Ảnh đại diện',
            'type' => 'Loại danh mục'
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

    public static function getListStatus()
    {
        return
            $credential_status = [
                self::STATUS_ACTIVE => Yii::t('app', 'Hoạt động'),
                self::STATUS_INACTIVE => Yii::t('app', 'Tạm dừng'),
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
}

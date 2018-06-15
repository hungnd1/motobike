<?php

namespace common\models;

use Yii;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'required'],
            [['description'], 'string'],
            [['status', 'order_number', 'created_at', 'updated_at','fruit_id'], 'integer'],
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
            'fruit_id' => 'Cây trồng'
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

    public static function getListStatus(){
        return
            $credential_status = [
                self::STATUS_ACTIVE => Yii::t('app','Hoạt động'),
                self::STATUS_INACTIVE => Yii::t('app','Tạm dừng'),
            ];
    }

    public static function getFruits()
    {
        $arrFruit = [];
        $listFruit  = Fruit::find()->all();
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
}

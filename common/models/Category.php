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
            [['status', 'order_number', 'created_at', 'updated_at'], 'integer'],
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
}

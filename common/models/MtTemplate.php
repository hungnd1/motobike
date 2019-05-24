<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mt_template".
 *
 * @property integer $id
 * @property string $mo_key
 * @property string $content
 * @property string $station_code
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class MtTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mt_template';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mo_key','content'],'required'],
            [['content','station_code'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['mo_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mo_key' => 'Mo Key',
            'content' => 'Nội dung',
            'status' => 'Trạng thái',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'station_code' => 'Mã xã'
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
}

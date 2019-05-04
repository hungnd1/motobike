<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "app_param".
 *
 * @property integer $id
 * @property string $param_key
 * @property string $param_value
 * @property string $content
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class AppParam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_param';
    }

    /**
     * @inheritdoc
     */

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    public function rules()
    {
        return [
            [['param_key','param_value'], 'required'],
            [['param_value', 'content'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['param_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'param_key' => 'Key cấu hình',
            'param_value' => 'Giá trị cấu hình',
            'content' => 'Nội dung',
            'status' => 'Trạng thái',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

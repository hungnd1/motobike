<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "file_manage".
 *
 * @property integer $id
 * @property string $display_name
 * @property integer $category_id
 * @property integer $type
 * @property string $file
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type_extension
 */
class FileManage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file_manage';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const TYPE_CONFIRM = 1;
    const TYPE_DOCUMENT = 2;

    const EXCEL = 1;
    const PDF = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name','status','category_id'],'required'],
            [['category_id', 'type', 'status', 'created_at', 'updated_at','type_extension'], 'integer'],
            [['file'], 'string'],
            ['file', 'required','on' => 'create'],
            [['display_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Tên file',
            'category_id' => 'Danh mục',
            'type' => 'Loại',
            'file' => 'File',
            'status' => 'Trạng thái',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function validateUnique($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findUser($this->username);
            if($user){
                $this->addError($attribute, Yii::t('app','Tên tài khoản đã tồn tại trong hệ thống'));
            }
        }
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

    public static function lstType()
    {
        $lst = [
            self::TYPE_CONFIRM => \Yii::t('app', 'Tài liệu chứng nhận'),
            self::TYPE_DOCUMENT => \Yii::t('app', 'Tài liệu tham khảo'),
        ];
        return $lst;
    }

    public function getTypeName()
    {
        $lst = self::lstType();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }

    public function getFileLink()
    {
        return $this->file ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->file, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

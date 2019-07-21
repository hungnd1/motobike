<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $file_company_file
 * @property string $file
 * @property string $description
 * @property integer $status
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }
    public $fileCompanyProfile;

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['username','password','file'],'required'],
            [['username'], 'string', 'max' => 255],
            [['password', 'file','file_company_file'], 'string', 'max' => 500],
            [['description'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'file' => 'File hồ sơ công ty',
            'status' => 'Trạng thái',
            'file_company_file' => 'Upload danh sách nông dân công ty'
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

    public static function getTemplateFilePrice() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/Thong tin cong ty (template)-VN.docx';
    }

    public static function getTemplateFile() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/DS nong dan_VN.xlsx';
    }

    public function getImageLink()
    {
        return $this->file ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->file, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

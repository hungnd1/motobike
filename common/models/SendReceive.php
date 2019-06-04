<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "send_receive".
 *
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $carrier
 * @property integer $error_code
 * @property string $description
 */
class SendReceive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'send_receive';
    }

    public $mt_template_id;
    public $import;
    public $input;
    public $errorFile;
    public $fileUpload;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text','import','input'], 'string'],
            [['created_at', 'updated_at', 'status', 'error_code','mt_template_id'], 'integer'],
            [['from'], 'string', 'max' => 500],
            [['to', 'carrier', 'description'], 'string', 'max' => 500],
            [['fileUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xlsx,csv', 'maxFiles' => 1],
            [['errorFile'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'Số điện thoại nhận tin nhắn',
            'to' => 'Số điện thoại nhận tin nhắn',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'carrier' => 'Carrier',
            'error_code' => 'Error Code',
            'description' => 'Description',
            'mt_template_id' => 'Mẫu tin nhắn',
            'fileUpload'=>'File import'
        ];
    }

    public static function getTemplateFilePrice() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/templateImport.xlsx';
    }
}

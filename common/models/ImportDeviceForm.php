<?php
/**
 * Created by PhpStorm.
 * User: bibon
 * Date: 5/13/2016
 * Time: 10:26 AM
 */

namespace common\models;


use Yii;
use yii\base\Model;

class ImportDeviceForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $templateFile = '/backend/web/ImportDevicesTemplate.xlsx';
    public $uploadedFile;
    public $errorFile;

    public function rules()
    {
        return [
            [['templateFile'], 'string'],
            [['uploadedFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx', 'maxFiles' => 1],
            [['errorFile'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uploadedFile' => Yii::t('app', 'Tệp excel'),
            'errorFile' => Yii::t('app', 'Tệp lỗi'),
        ];
    }

    public static function getTemplateFile() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/Locations_Data_Lizard_072017.xlsx';
    }

    public function getEditTemplateFile(){
        return Yii::$app->getUrlManager()->getBaseUrl() . '/EditDevicesTemplate.xlsx';

    }
}
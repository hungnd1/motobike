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
use yii\web\UploadedFile;

class ImportDeviceForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $templateFile = '/backend/web/ImportDevicesTemplate.xlsx';
    public $templateFilePrice = '/backend/web/Coffeeprices_Central_Highlands_Vietnam.csv';
    public $uploadedFile;
    public $errorFile;

    public function rules()
    {
        return [
            [['templateFile'], 'string'],
            [['uploadedFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx,csv', 'maxFiles' => 1],
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
        return Yii::$app->getUrlManager()->getBaseUrl() . '/Cleaned_and_processed.xlsx';
    }

    public static function getTemplateFilePrice() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/Coffeeprices_Central_Highlands_Vietnam.csv';
    }

    public static function getTemplateFileWeather() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/Coffeeprices_Central_Highlands_Vietnam.csv';
    }

    public function getEditTemplateFile(){
        return Yii::$app->getUrlManager()->getBaseUrl() . '/EditDevicesTemplate.xlsx';

    }
}
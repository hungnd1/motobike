<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "banner".
 *
 * @property integer $id
 * @property string $banner
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banner'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'banner' => 'Banner',
        ];
    }

    public function getImageLink()
    {
        return $this->banner ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->banner, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

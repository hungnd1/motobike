<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "fruit".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property integer $parent_id
 * @property integer $have_child
 */
class Fruit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fruit';
    }

    const COFFEE = 1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'image'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['parent_id', 'have_child'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên loại cây trồng',
            'image' => 'Hình ảnh',
            'parent_id' => 'Cây cha'
        ];
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

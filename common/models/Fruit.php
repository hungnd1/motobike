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
 * @property integer $is_primary
 * @property integer $order
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
    const CAPHE_VOI = 1;
    const CAPHE_CHE = 2;

    const IS_PRIMARY = 1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'image'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['parent_id', 'have_child','is_primary','order'], 'integer']
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
            'parent_id' => 'Cây cha',
            'is_primary' => 'Cây trồng chính',
            'order' => 'Sắp xếp'
        ];
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ra_film_document".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property integer $fruit_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class RaFilmDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ra_film_document';
    }


    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fruit_id', 'title', 'description', 'url', 'status'], 'required'],
            [['fruit_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['description', 'url'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'description' => 'Mô tả',
            'url' => 'Link film',
            'fruit_id' => 'Cây trồng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
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

    public static function getFruits()
    {
        $arrFruit = [];
        $listFruit = Fruit::find()->all();
        foreach ($listFruit as $item) {
            /** @var $item Fruit */
            $arrFruit[$item->id] = $item->name;
        }
        return $arrFruit;
    }

    public function getFruitName($fruit_id)
    {
        $lst = self::getFruits();
        if (array_key_exists($fruit_id, $lst)) {
            return $lst[$fruit_id];
        }
        return $fruit_id;
    }


    public function getYouTubeCode($link)

    {

        $pos = strrpos($link, '/');

        if ($pos !== false)

            return substr($link, $pos);

        else

            return '';
    }
}

<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "gap_general".
 *
 * @property integer $id
 * @property string $gap
 * @property string $content_2
 * @property string $content_3
 * @property string $content_4
 * @property string $content_5
 * @property string $content_6
 * @property string $content_7
 * @property string $content_8
 * @property string $content_9
 * @property string $title
 * @property string $image
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property integer $order
 * @property float $temperature_max
 * @property float $temperature_min
 * @property float $precipitation_max
 * @property float $precipitation_min
 * @property float $windspeed_max
 * @property float $windspeed_min
 * @property integer $fruit_id
 */
class GapGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gap_general';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const GAP_GENERAL = 1; // sau benh
    const GAP_DETAIL = 2; // chi tiet gap
    const CLIMATE_CHANGE = 3; //bien doi khi hau

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gap', 'title','fruit_id'], 'required'],
            [['gap', 'title', 'image'], 'string'],
            [['content_2', 'content_3', 'content_4', 'content_5', 'content_6', 'content_7',
                'content_8', 'content_9'
            ], 'string'],
            [['status', 'created_at', 'updated_at', 'type', 'order','fruit_id'], 'integer'],
            [['temperature_max', 'temperature_min', 'windspeed_min', 'windspeed_max', 'precipitation_max', 'precipitation_min'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gap' => 'Làm đất/Làm đất, chuẩn bị hố và trồng tiêu',
            'content_2' => 'Trồng mới, trồng lại và chăm sóc cà phê/Chăm sóc thường xuyên tiêu từ năm một đến năm ba',
            'content_3' => 'Phân bón/Chăm sóc thường xuyên tiêu kinh doanh',
            'content_4' => 'Tưới nước/Phòng trừ sâu bệnh cho tiêu kinh doanh',
            'content_5' => 'Phun thuốc/Đôn tiêu',
            'content_6' => 'Thu hoạch/Sơ chế bảo quản',
            'content_7' => 'Sơ chế',
            'content_8' => 'Chuẩn bị giống - vườn ươm/Chọn lựa và trồng choái cho tiêu leo',
            'content_9' => 'Tạo hình/Thu hái',
            'status' => 'Trạng thái',
            'title' => 'Tiêu đề',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'temperature_max' => 'Nhiệt độ lớn nhất',
            'temperature_min' => 'Nhiệt độ nhỏ nhất',
            'windspeed_min' => 'Tốc độ gió nhỏ nhất (km/h)',
            'windspeed_max' => 'Tốc độ gió lớn nhất (km/h)',
            'precipitation_max' => 'Lượng mưa lớn nhất (mm)',
            'precipitation_min' => 'Lượn mưa nhỏ nhất (mm)',
            'image' => 'Ảnh đại diện',
            'order' => 'Sắp xếp',
            'fruit_id' => 'Cây trồng'
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

    public static function getListStatusNameByStatus($status)
    {
        $lst = self::listStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }

    public static function getFruits()
    {
        $arrFruit = [];
        $listFruit  = Fruit::find()->all();
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
}

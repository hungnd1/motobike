<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "price_coffee".
 *
 * @property integer $id
 * @property string $province_id
 * @property integer $price_average
 * @property integer $unit
 * @property string $organisation_name
 * @property integer $coffee_old_id
 * @property integer $last_time_value
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceCoffee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_coffee';
    }

    const UNIT_VND = 1;
    const UNIT_USD = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_average', 'province_id', 'unit'], 'required'],
            [['coffee_old_id', 'last_time_value', 'price_average', 'unit', 'created_at', 'updated_at'], 'integer'],
            [['province_id', 'organisation_name'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => 'Tỉnh,Xã',
            'price_average' => 'Giá trung bình',
            'unit' => 'Đơn vị',
            'organisation_name' => 'Doanh nghiệp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getListProvince()
    {

        $listArr = array();
        $lstPro = Province::find()->all();
        foreach ($lstPro as $item) {
            /** @var $item Province */
            $listArr[$item->id] = $item->province_name;
        }
        return $listArr;
    }

    public static function getListDistrict()
    {

        $listArr = array();
        $lstPro = District::find()->all();
        foreach ($lstPro as $item) {
            /** @var $item District */
            $listArr[$item->id] = $item->district_name;
        }
        return $listArr;
    }

    public static function getProvinceDetail($province_id)
    {
        $province = Province::findOne(['id' => $province_id]);
        return $province->province_name;
    }

    public static function getListUnit()
    {
        return
            $credential_status = [
                self::UNIT_VND => Yii::t('app', 'VND/kg'),
                self::UNIT_USD => Yii::t('app', 'USD($)/tấn'),
            ];
    }

    public static function getListStatusNameByUnit($unit)
    {
        $lst = self::getListUnit();
        if (array_key_exists($unit, $lst)) {
            return $lst[$unit];
        }
        return $unit;
    }

    public static function getPrice($date)
    {
        $from_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
        $to_time = strtotime(str_replace('/', '-', $date) . ' 23:59:59');
        $pricePre = \api\models\PriceCoffee::find()
            ->andWhere(['>=', 'created_at', $from_time])
            ->andWhere(['<=', 'created_at', $to_time])
            ->groupBy('coffee_old_id')
            ->orderBy(['coffee_old_id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $pricePre,
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function getContentProvider()
    {
        $listCp = Province::find()->all();
        return $listCp;
    }
}

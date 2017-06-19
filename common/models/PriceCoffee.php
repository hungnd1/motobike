<?php

namespace common\models;

use common\helpers\CUtils;
use Yii;

/**
 * This is the model class for table "price_coffee".
 *
 * @property integer $id
 * @property integer $province_id
 * @property integer $district_id
 * @property integer $price_average
 * @property integer $unit
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
            [['price_average', 'province_id', 'unit', 'district_id'], 'required'],
            [['province_id', 'price_average', 'district_id', 'unit', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => 'Tỉnh',
            'district_id' => 'Huyện/Thành phố',
            'price_average' => 'Giá trung bình',
            'unit' => 'Đơn vị',
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

    public static function getDistrictDetail($district_id)
    {
        $province = District::findOne(['id' => $district_id]);
        return $province->district_name;
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
        $arr = [];
        $province_return = [];
        $listProvince = Province::find()->all();
        foreach ($listProvince as $item) {
            /** @var $item Province */
            $province = [];
            $province['province_name'] = $item->province_name;
            $listPrice = PriceCoffee::find()->andWhere(['province_id' => $item->id])
                ->andFilterWhere(['>=', 'created_at', $from_time])
                ->andFilterWhere(['<=', 'created_at', $to_time])->all();
            if ($listPrice) {
                foreach ($listPrice as $price) {
                    /** @var $price PriceCoffee */
                    $arrPrice['name'] = District::findOne(['id' => $price->district_id])->district_name;
                    $pricePre = PriceCoffee::find()->andWhere(['province_id' => $item->id, 'district_id' => $price->district_id])->orderBy(['id' => SORT_DESC])->one();
                    /** @var $pricePre PriceCoffee */
                    if ($pricePre) {
                        $arrPrice['change_info'] = $price->price_average - $pricePre->price_average < 0 ? 'Trừ lùi: ' . $price->price_average - $pricePre->price_average : '+' . $price->price_average - $pricePre->price_average;
                    } else {
                        $arrPrice['change_info'] = 0;
                    }
                    $arrPrice['price_average'] = CUtils::formatPrice($price->price_average);
                    $arrPrice['unit'] = PriceCoffee::getListStatusNameByUnit($price->unit);
                    $province['items'][] = $arrPrice;
                }
            }
            $province_return[] = $province;
        }
        $arr['items'] = $province_return;
        return $arr;
    }

    public function getContentProvider()
    {
        $listCp = Province::find()->all();
        return $listCp;
    }
}

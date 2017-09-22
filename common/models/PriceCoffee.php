<?php

namespace common\models;

use Yii;

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

    const TYPE_COFFEE_A = 'Cà phê Chè';
    const TYPE_COFFEE_V = 'Cà phê vối';

    const COMPANY_AGENT = 'Đại lý';
    const COMPANY_COMPANY = 'Công ty';
    const COMPANY_EXPORT = 'Xuất khẩu';
    const COMPANY_FARM_GATE = 'Cổng trại';

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

    public static function getPrice($date, $province_id)
    {
        $from_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
        $to_time = strtotime(str_replace('/', '-', $date) . ' 23:59:59');
        $pricePre = \api\models\PriceCoffee::find()
            ->innerJoin('station', 'station.station_code = price_coffee.province_id')
            ->andWhere(['station.province_id' => $province_id])
            ->andWhere(['>', 'price_coffee.created_at', $from_time + 7 * 60 * 60])
            ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60])
            ->andWhere(['not in','price_coffee.coffee_old_id',['201029','199811','199808','199807']])
            ->groupBy('price_coffee.coffee_old_id')
            ->orderBy(['price_coffee.coffee_old_id' => SORT_DESC])->all();
        return $pricePre;
    }

    public function getContentProvider()
    {
        $listCp = Province::find()->all();
        return $listCp;
    }

    public static function getPriceCode($code)
    {
        switch ($code) {
            case 'dABA':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
                break;
            case 'dABC':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dABE':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_EXPORT
                ];
                break;
            case 'dABF':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_FARM_GATE
                ];
                break;
            case 'dACA':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
                break;
            case 'dACC':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dACE':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_EXPORT
                ];
                break;
            case 'dACF':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_FARM_GATE
                ];
                break;
            case 'dRBA':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
                break;
            case 'dRBC':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dRBE':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_EXPORT
                ];
                break;
            case 'dRBF':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_FARM_GATE
                ];
                break;
            case 'dRCA':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
                break;
            case 'dRCC':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dRCE':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_EXPORT
                ];
                break;
            case 'dRCF':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_V,
                    'company' => PriceCoffee::COMPANY_FARM_GATE
                ];
                break;
            default:
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFEE_A,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
        }
    }
}

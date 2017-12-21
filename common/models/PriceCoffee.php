<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "price_coffee".
 *
 * @property integer $id
 * @property string $province_id
 * @property string $price_average
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

    const TYPE_COFFE_TUOI = 'Quả tươi vối';
    const TYPE_QUA_TUOI = 'Quả tươi chè';
    const TYPE_COFFE_CHE = 'Cafe chè';
    const TYPE_COFFE_VOI = 'Cafe vối';
    const TYPE_COFFEE_A = 'Nhân xô chè';
    const TYPE_COFFEE_V = 'Nhân xô vối';

    const COMPANY_AGENT = 'Đại lý';
    const COMPANY_COMPANY = 'Công ty';
    const COMPANY_EXPORT = 'Xuất khẩu';
    const COMPANY_FARM_GATE = 'Cổng trại';

    const TYPE_EXPORT = 2;
    const TYPE_NORMAL = 1;

    const TYPE_QUATUOIVOI  = 1;
    const TYPE_QUATUOICHE = 2;
    const TYPE_NHANXOCHE = 3;
    const TYPE_NHANXOVOI = 4;
    const TYPE_GIASAN = 5;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_average', 'province_id', 'unit'], 'required'],
            [['coffee_old_id', 'last_time_value', 'unit', 'created_at', 'updated_at'], 'integer'],
            [['province_id', 'organisation_name', 'price_average'], 'safe']
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

    public static function getPrice($date, $province_id, $type = PriceCoffee::TYPE_NORMAL,$key = null)
    {
        $from_time = strtotime(str_replace('/', '-', $date) . ' 00:00:00');
        $to_time = strtotime(str_replace('/', '-', $date) . ' 23:59:59');
        if ($type == PriceCoffee::TYPE_NORMAL) {
            $pricePre = \api\models\PriceCoffee::find()
                ->innerJoin('station', 'station.station_code = price_coffee.province_id')
                ->andWhere(['station.province_id' => $province_id])
                ->andWhere(['>=', 'price_coffee.created_at', $from_time + 7 * 60 * 60])
                ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60])
                ->andWhere(['not in', 'price_coffee.organisation_name', ['dRBE', 'dRCL', 'dACN','dABE','dACE','dRCE']])
                ->andWhere(['in','price_coffee.organisation_name',$key])
                ->orderBy(['price_coffee.province_id' => SORT_DESC])->all();
            if(!$pricePre){
                $pricePre = \api\models\PriceCoffee::find()
                    ->innerJoin('station', 'station.station_code = price_coffee.province_id')
                    ->andWhere(['station.province_id' => $province_id])
                    ->andWhere(['>=', 'price_coffee.created_at', $from_time + 7 * 60 * 60 - 86400])
                    ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60 - 86400])
                    ->andWhere(['not in', 'price_coffee.organisation_name', ['dRBE', 'dRCL', 'dACN','dABE','dACE','dRCE']])
                    ->andWhere(['in','price_coffee.organisation_name',$key])
                    ->orderBy(['price_coffee.province_id' => SORT_DESC])->all();
                if(!$pricePre){
                    $pricePre = \api\models\PriceCoffee::find()
                        ->innerJoin('station', 'station.station_code = price_coffee.province_id')
                        ->andWhere(['station.province_id' => $province_id])
                        ->andWhere(['>=', 'price_coffee.created_at', $from_time + 7 * 60 * 60 - 2 * 86400])
                        ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60 - 2 * 86400])
                        ->andWhere(['not in', 'price_coffee.organisation_name', ['dRBE', 'dRCL', 'dACN','dABE','dACE','dRCE']])
                        ->andWhere(['in','price_coffee.organisation_name',$key])
                        ->orderBy(['price_coffee.province_id' => SORT_DESC])->all();
                }
            }
        } else {
            $pricePre = \api\models\PriceCoffee::find()
                ->andWhere(['in', 'price_coffee.organisation_name', ['dRBE', 'dRCL', 'dACN','dABE','dACE','dRCE']])
                ->andWhere(['>=', 'price_coffee.created_at', $from_time + 7 * 60 * 60])
                ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60])
                ->orderBy(['price_coffee.province_id' => SORT_ASC])->all();
            if(!$pricePre){
                $pricePre = \api\models\PriceCoffee::find()
                    ->andWhere(['in', 'price_coffee.organisation_name', ['dRBE', 'dRCL', 'dACN','dABE','dACE','dRCE']])
                    ->andWhere(['>=', 'price_coffee.created_at', $from_time + 7 * 60 * 60 - 86400])
                    ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60 - 86400])
                    ->orderBy(['price_coffee.province_id' => SORT_ASC])->all();
                if(!$pricePre){
                    $pricePre = \api\models\PriceCoffee::find()
                        ->andWhere(['in', 'price_coffee.organisation_name', ['dRBE', 'dRCL', 'dACN','dABE','dACE','dRCE']])
                        ->andWhere(['>=', 'price_coffee.created_at', $from_time + 7 * 60 * 60 - 2 * 86400])
                        ->andWhere(['<=', 'price_coffee.created_at', $to_time + 7 * 60 * 60 - 2 * 86400])
                        ->orderBy(['price_coffee.province_id' => SORT_ASC])->all();
                }
            }
        }

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
                    'name_coffee' => PriceCoffee::TYPE_QUA_TUOI,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
                break;
            case 'dACC':
                return [
                    'name_coffee' => PriceCoffee::TYPE_QUA_TUOI,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dACN':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_CHE,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dRCL':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_VOI,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dACE':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_TUOI,
                    'company' => PriceCoffee::COMPANY_EXPORT
                ];
                break;
            case 'dACF':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_TUOI,
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
                    'name_coffee' => PriceCoffee::TYPE_COFFE_TUOI,
                    'company' => PriceCoffee::COMPANY_AGENT
                ];
                break;
            case 'dRCC':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_TUOI,
                    'company' => PriceCoffee::COMPANY_COMPANY
                ];
                break;
            case 'dRCE':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_TUOI,
                    'company' => PriceCoffee::COMPANY_EXPORT
                ];
                break;
            case 'dRCF':
                return [
                    'name_coffee' => PriceCoffee::TYPE_COFFE_TUOI,
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

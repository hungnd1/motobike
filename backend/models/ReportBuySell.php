<?php

namespace backend\models;

use common\models\Province;
use common\models\ReportBuySellSearch;
use common\models\Subscriber;
use common\models\SubscriberActivity;
use common\models\SubscriberActivitySearch;
use common\models\TypeCoffee;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;


/**
 * Login form
 */
class ReportBuySell extends Model
{
    const TYPE_DATE = 1;
    const TYPE_MONTH = 2;

    public $to_month;
    public $to_date;
    public $from_date;
    public $from_month;
    public $province_id;
    public $type_coffee;
    public $dataProvider;
    public $content = null;
    public $site_id = null;
    public $service_id = null;
    public $type = self::TYPE_DATE;
    public $content_type = null;

    public $list_type = [self::TYPE_DATE => 'Theo ngày', self::TYPE_MONTH => 'Theo tháng'];

    public function rules()
    {
        return [
            [['from_date', 'to_date', 'to_month', 'from_month', 'province_id', 'type_coffee'], 'safe'],
            [['from_date'], 'required',
//                'when' => function($model) {
//                    return $model->type == self::TYPE_DATE;
//                },
                'message' => Yii::t('app', 'Thông tin không hợp lệ, Ngày bắt đầu không được để trống'),
            ],
            [['to_date'], 'required',
//                'when' => function($model) {
//                    return $model->type == self::TYPE_DATE;
//                },
                'message' => Yii::t('app', 'Thông tin không hợp lệ, Ngày kết thúc không được để trống'),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'to_date' => Yii::t('app', 'Đến ngày'),
            'from_date' => Yii::t('app', 'Từ ngày'),
            'to_month' => Yii::t('app', 'Đến tháng'),
            'from_month' => Yii::t('app', 'Từ tháng'),
            'type' => Yii::t('app', 'Loại báo cáo'),
            'province_id' => Yii::t('app', 'Tỉnh'),
            'type_coffee' => Yii::t('app', 'Loại coffee')
        ];
    }

    /**
     *
     */
    public function generateReport()
    {
        if ($this->from_date != '' && DateTime::createFromFormat("d/m/Y", $this->from_date)) {
            $from_date = DateTime::createFromFormat("d/m/Y", $this->from_date)->setTime(0, 0)->format('Y-m-d H:i:s');
        } else {
            $from_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
        }

        if ($this->to_date != '' && DateTime::createFromFormat("d/m/Y", $this->to_date)) {
            $to_date = DateTime::createFromFormat("d/m/Y", $this->to_date)->setTime(0, 0)->format('Y/m/d H:i:s');
        } else {
            $to_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
        }

        $from_date = strtotime(str_replace('/', '-', $this->from_date) . ' 00:00:00');
        $to_date = strtotime(str_replace('/', '-', $this->to_date) . ' 23:59:59');

        $param = Yii::$app->request->queryParams;
        $searchModel = new ReportBuySellSearch();
        $param['ReportBuySellSearch']['from_date'] = $from_date;
        $param['ReportBuySellSearch']['to_date'] = $to_date;
        $param['ReportBuySellSearch']['province_id'] = $this->province_id;
        $param['ReportBuySellSearch']['type_coffee'] = $this->type_coffee;
        $dataProvider = $searchModel->searchReport($param);
        return $dataProvider;

    }


    public function generateReportAll()
    {
        if ($this->from_date != '' && DateTime::createFromFormat("d/m/Y", $this->from_date)) {
            $from_date = DateTime::createFromFormat("d/m/Y", $this->from_date)->setTime(0, 0)->format('Y-m-d H:i:s');
        } else {
            $from_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
        }

        if ($this->to_date != '' && DateTime::createFromFormat("d/m/Y", $this->to_date)) {
            $to_date = DateTime::createFromFormat("d/m/Y", $this->to_date)->setTime(0, 0)->format('Y/m/d H:i:s');
        } else {
            $to_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
        }

        $from_date = strtotime(str_replace('/', '-', $this->from_date) . ' 00:00:00');
        $to_date = strtotime(str_replace('/', '-', $this->to_date) . ' 23:59:59');

        $param = Yii::$app->request->queryParams;
        $searchModel = new ReportBuySellSearch();
        $param['ReportBuySellSearch']['from_date'] = $from_date;
        $param['ReportBuySellSearch']['to_date'] = $to_date;
        $param['ReportBuySellSearch']['province_id'] = $this->province_id;
        $param['ReportBuySellSearch']['type_coffee'] = $this->type_coffee;

        $dataProvider = $searchModel->searchReportAll($param);
        return $dataProvider;

    }

    public function generateDetailReport($rawData, $dateFormat = 'd/m/Y')
    {
        $dataRow = [];
        //label header
        $sttLabel = Yii::t('app', 'STT');
        $province = Yii::t('app', 'Tỉnh');
        $type = Yii::t('app', 'Loại Coffee');
        $total_via_site_label = Yii::t('app', 'Tổng mua');
        $total_sell_site_label = Yii::t('app', 'Tổng bán');
        $dateLabel = Yii::t('app', 'Ngày báo cáo');
        if (!empty($rawData)) {
            $i = 0;
            foreach ($rawData as $raw) {
                $row[$sttLabel] = ++$i;
                $row[$dateLabel] = date($dateFormat, $raw['report_date']);
                $row[$province] = Province::findOne($raw['province_id'])->province_name;
                $row[$type] = TypeCoffee::findOne($raw['type_coffee'])->name;
                $row[$total_via_site_label] = $raw['total_buy'];
                $row[$total_sell_site_label] = $raw['total_sell'];
                $dataRow[] = $row;

                //kết thúc một ngày, khởi tạo thêm 1 dòng cho ngày tiếp theo
                $row = [];
            }
            //tinh tong cac  cot  dữ liệu

        }
        $excelDataProvider = new ArrayDataProvider([
            'allModels' => $dataRow,
            'pagination' => false
        ]);
        return $excelDataProvider;
    }
}

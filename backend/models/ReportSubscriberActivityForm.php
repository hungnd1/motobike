<?php

namespace backend\models;

use common\models\ReportSubscriberActivitySearch;
use common\models\ReportSubscriberDailySearch;
use common\models\SubscriberActivityType;
use common\models\SubscriberActivityTypeSearch;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;


/**
 * Login form
 */
class ReportSubscriberActivityForm extends Model
{
    const TYPE_DATE = 1;
    const TYPE_MONTH = 2;

    public $to_month;
    public $to_date;
    public $from_date;
    public $from_month;
    public $dataProvider;
    public $content = null;
    public $site_id = null;
    public $service_id = null;
    public $type = self::TYPE_DATE;
    public $content_type = null;


    public $weather;
    public $price;
    public $buy;
    public $gap_disease;
    public $gap;
    public $qa;
    public $tracuusuco;
    public $nongnghiepthongminh;
    public $biendoikhihau;
    public $tuvansudungphanbon;

    public $list_type = [self::TYPE_DATE => 'Theo ngày', self::TYPE_MONTH => 'Theo tháng'];

    public function rules()
    {
        return [
            [['weather', 'price', 'buy', 'gap_disease', 'gap', 'qa', 'tracuusuco', 'nongnghiepthongminh', 'biendoikhihau', 'tuvansudungphanbon'], 'safe'],
            [['from_date', 'to_date', 'content', 'site_id', 'service_id', 'to_month', 'from_month', 'type', 'content_type'], 'safe'],
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
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'to_date' => Yii::t('app', 'Đến ngày'),
            'from_date' => Yii::t('app', 'Từ ngày'),
            'service_id' => Yii::t('app', 'Gói cước'),
            'to_month' => Yii::t('app', 'Đến tháng'),
            'from_month' => Yii::t('app', 'Từ tháng'),
            'type' => Yii::t('app', 'Loại báo cáo'),
            'site_id' => Yii::t('app', 'Nhà cung cấp dịch vụ'),
            'content_type' => \Yii::t('app', 'Loại nội dung'),
            'weather' => Yii::t('app', 'Thời tiết'),
            'price' => Yii::t('app', 'Giá cả'),
            'buy' => Yii::t('app', 'Mua bán'),
            'gap_disease' => Yii::t('app', 'Sâu bệnh'),
            'gap' => Yii::t('app', 'Tài liệu kỹ thuật canh tác'),
            'qa' => Yii::t('app', 'Hỏi đáp'),
            'tracuusuco' => Yii::t('app', 'Tra cứu sự cố bất thường'),
            'nongnghiepthongminh' => Yii::t('app', 'Nông nghiệp thông minh'),
            'biendoikhihau' => Yii::t('app', 'Biến đổi khí hậu'),
            'tuvansudungphanbon' => Yii::t('app', 'Tư vấn sử dụng phân bón'),
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
        $searchModel = new ReportSubscriberActivitySearch();
        $param['ReportSubscriberActivitySearch']['from_date'] = $from_date;
        $param['ReportSubscriberActivitySearch']['to_date'] = $to_date;

        $dataProvider = $searchModel->search($param);
        return $dataProvider;

    }

    public function generateReportAll1()
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
        $searchModel = new ReportSubscriberActivitySearch();
        $param['ReportSubscriberActivitySearch']['from_date'] = $from_date;
        $param['ReportSubscriberActivitySearch']['to_date'] = $to_date;

        $dataProvider = $searchModel->searchAll($param);
        return $dataProvider;

    }

    public function generateDetailReport($rawData, $dateFormat = 'd/m/Y')
    {
        $dataRow = [];
        //label header
        $sttLabel = Yii::t('app', 'STT');
        $dateLabel = Yii::t('app', 'Ngày');
        $total_via_site_label = Yii::t('app', 'Tổng lượt truy cập');
        $total_via_site_daily_label = Yii::t('app', 'Số lượt truy cập trong ngày');
        $total_via_android_label = Yii::t('app', 'Từ ứng dụng');
        $total_via_website_label = Yii::t('app', 'Từ website');
        $total_via_site_daily = 0;
        $total_via_android = 0;
        $total_via_website = 0;
        $total_via = 0;
        $total_via_sub = 0;
        if (!empty($rawData)) {
            $i = 0;
            foreach ($rawData as $raw) {
                if ($i == 0) {
                    $total_via = $raw['total_via_site'];
                }
                if ($i == sizeof($rawData) - 1) {
                    $total_via_sub = $raw['total_via_site'];
                }
                $row[$sttLabel] = ++$i;
                $row[$dateLabel] = date($dateFormat, $raw['report_date']);
                $row[$total_via_site_label] = $raw['total_via_site'];
                $row[$total_via_site_daily_label] = $raw['via_site_daily'];
                $row[$total_via_android_label] = $raw['via_android'];
                $row[$total_via_website_label] = $raw['via_website'];
                $dataRow[] = $row;

                $total_via_site_daily += $raw['via_site_daily'];
                $total_via_android += $raw['via_android'];
                $total_via_website += $raw['via_website'];
                //kết thúc một ngày, khởi tạo thêm 1 dòng cho ngày tiếp theo
                $row = [];
            }
            //tinh tong cac  cot  dữ liệu
            $row[$sttLabel] = ++$i;
            $row[$dateLabel] = 'Tổng';
            $row[$total_via_site_label] = $total_via - $total_via_sub;
            $row[$total_via_site_daily_label] = $total_via_site_daily;
            $row[$total_via_android_label] = $total_via_android;
            $row[$total_via_website_label] = $total_via_website;
            $dataRow[] = $row;

        }
        $excelDataProvider = new ArrayDataProvider([
            'allModels' => $dataRow,
            'pagination' => false,
        ]);
        return $excelDataProvider;
    }


    public function generateReportType()
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
        $searchModel = new SubscriberActivityTypeSearch();
        $param['SubscriberActivityTypeSearch']['from_date'] = $from_date;
        $param['SubscriberActivityTypeSearch']['to_date'] = $to_date;

        $dataProvider = $searchModel->search($param);
        return $dataProvider;

    }

    public function generateReportAllType()
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
        $searchModel = new SubscriberActivityTypeSearch();
        $param['SubscriberActivityTypeSearch']['from_date'] = $from_date;
        $param['SubscriberActivityTypeSearch']['to_date'] = $to_date;

        $dataProvider = $searchModel->searchAll($param);
        return $dataProvider;

    }

    public function generateDetailReportType($rawData, $dateFormat = 'd/m/Y')
    {
        $dataRow = [];
        //label header
        $sttLabel = Yii::t('app', 'STT');
        $dateLabel = Yii::t('app', 'Ngày');
        $total_via_site_label = Yii::t('app', 'Tổng lượt truy cập trong ngày');
        $weather_label = Yii::t('app', 'Thời tiết');
        $price_label = Yii::t('app', 'Giá cả');
        $buy_label = Yii::t('app', 'Mua bán');
        $gap_disease_label = Yii::t('app', 'Sâu bệnh');
        $gap_label = Yii::t('app', 'Tài liệu kỹ thuật canh tác');
        $qa_label = Yii::t('app', 'Hỏi đáp');
        $tracuusuco_label = Yii::t('app', 'Tra cứu sự cố bất thường');
        $nongnghiepthongminh_label = Yii::t('app', 'Nông nghiệp thông minh');
        $biendoikhihau_label = Yii::t('app', 'Biến đổi khí hậu');
        $tuvansudungphanbon_label = Yii::t('app', 'Tư vấn sử dụng phân bón');

        $total_via_site = 0;
        $weather = 0;
        $price = 0;
        $buy = 0;
        $gap_disease = 0;
        $gap = 0;
        $qa = 0;
        $tracuusuco = 0;
        $nongnghiepthongminh = 0;
        $biendoikhihau = 0;
        $tuvansudungphanbon = 0;
        if (!empty($rawData)) {
            $i = 0;
            foreach ($rawData as $raw) {
                $row[$sttLabel] = ++$i;
                $row[$dateLabel] = date($dateFormat, $raw['report_date']);
                $row[$weather_label] = $raw['weather'];
                $row[$price_label] = $raw['price'];
                $row[$buy_label] = $raw['buy'];
                $row[$gap_disease_label] = $raw['gap_disease'];
                $row[$gap_label] = $raw['gap'];
                $row[$qa_label] = $raw['qa'];
                $row[$tracuusuco_label] = $raw['tracuusuco'];
                $row[$nongnghiepthongminh_label] = $raw['nongnghiepthongminh'];
                $row[$biendoikhihau_label] = $raw['biendoikhihau'];
                $row[$tuvansudungphanbon_label] = $raw['tuvansudungphanbon'];
                $row[$total_via_site_label] = $raw['weather'] + $raw['price'] + $raw['buy']+$raw['gap_disease'] +  $raw['gap']
                    + $raw['qa'] + $raw['tracuusuco'] + $raw['nongnghiepthongminh'] + $raw['biendoikhihau'] + $raw['tuvansudungphanbon'];
                $dataRow[] = $row;
                $weather += $raw['weather'];
                $price += $raw['price'];
                $gap_disease += $raw['gap_disease'];
                $buy += $raw['buy'];
                $gap += $raw['gap'];
                $qa += $raw['qa'];
                $tracuusuco += $raw['tracuusuco'];
                $nongnghiepthongminh += $raw['nongnghiepthongminh'];
                $biendoikhihau += $raw['biendoikhihau'];
                $tuvansudungphanbon += $raw['tuvansudungphanbon'];

                    //kết thúc một ngày, khởi tạo thêm 1 dòng cho ngày tiếp theo
                $row = [];
            }
            //tinh tong cac  cot  dữ liệu
            $row[$sttLabel] = ++$i;
            $row[$dateLabel] = 'Tổng';
            $row[$total_via_site_label] = 0;
            $row[$weather_label] = $weather;
            $row[$price_label] = $price;
            $row[$buy_label] = $buy;
            $row[$gap_disease_label] = $gap_disease;
            $row[$gap_label] = $gap ;
            $row[$qa_label] = $qa;
            $row[$tracuusuco_label] = $tracuusuco;
            $row[$nongnghiepthongminh_label] = $nongnghiepthongminh;
            $row[$biendoikhihau_label] = $biendoikhihau;
            $row[$tuvansudungphanbon_label] = $tuvansudungphanbon;
            $dataRow[] = $row;

        }
        $excelDataProvider = new ArrayDataProvider([
            'allModels' => $dataRow,
            'pagination' => false,
        ]);
        return $excelDataProvider;
    }
}

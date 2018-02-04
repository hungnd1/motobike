<?php
namespace backend\models;

use common\models\ExchangeBuySearch;
use common\models\ReportSubscriberActivitySearch;
use common\models\ReportSubscriberDailySearch;
use common\models\SubscriberSearch;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;


/**
 * Login form
 */
class ReportBuyForm extends Model
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

    public $list_type = [self::TYPE_DATE => 'Theo ngày', self::TYPE_MONTH => 'Theo tháng'];

    public function rules()
    {
        return [
            [['from_date', 'to_date', 'content', 'site_id', 'service_id', 'to_month', 'from_month', 'type','content_type'], 'safe'],
            [['from_date'], 'required',
//                'when' => function($model) {
//                    return $model->type == self::TYPE_DATE;
//                },
                'message' => Yii::t('app','Thông tin không hợp lệ, Ngày bắt đầu không được để trống'),
            ],
            [['to_date'], 'required',
//                'when' => function($model) {
//                    return $model->type == self::TYPE_DATE;
//                },
                'message' => Yii::t('app','Thông tin không hợp lệ, Ngày kết thúc không được để trống'),
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'to_date' => Yii::t('app','Đến ngày'),
            'from_date' => Yii::t('app','Từ ngày'),
            'to_month' => Yii::t('app','Đến tháng'),
            'from_month' => Yii::t('app','Từ tháng'),
            'type' => Yii::t('app','Loại báo cáo'),
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
        $searchModel = new ExchangeBuySearch();
        $param['ExchangeBuySearch']['from_date'] =$from_date;
        $param['ExchangeBuySearch']['to_date'] =$to_date;

        $dataProvider = $searchModel->search($param);
        return  $dataProvider;

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
        $searchModel = new ExchangeBuySearch();
        $param['SubscriberSearch']['from_date'] =$from_date;
        $param['SubscriberSearch']['to_date'] =$to_date;

        $dataProvider = $searchModel->searchAll($param);
        return  $dataProvider;

    }
    public function generateDetailReport($rawData,$dateFormat = 'd/m/Y'){
        $dataRow = [];
        //label header
        $sttLabel = Yii::t('app','STT');
        $dateLabel = Yii::t('app','Ngày');
        $total_via_site_label = Yii::t('app','Tên tài khoản');
        if(!empty($rawData)){
            $i=0;
            foreach ($rawData as $raw){
                $row[$sttLabel] = ++$i;
                $row[$dateLabel] = date($dateFormat,$raw['created_at']);
                $row[$total_via_site_label] = $raw['username'];
                $dataRow[] = $row;

                //kết thúc một ngày, khởi tạo thêm 1 dòng cho ngày tiếp theo
                $row = [];
            }
            //tinh tong cac  cot  dữ liệu

        }
        $excelDataProvider = new ArrayDataProvider([
            'allModels' => $dataRow,
            'pagination'=>false
        ]);
        return $excelDataProvider;
    }
}

<?php
namespace backend\models;

use common\models\ReportSubscriberActivitySearch;
use common\models\ReportSubscriberDailySearch;
use common\models\Subscriber;
use common\models\SubscriberActivity;
use common\models\SubscriberSearch;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;


/**
 * Login form
 */
class ReportSubscriberForm extends Model
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
            'service_id' => Yii::t('app','Gói cước'),
            'to_month' => Yii::t('app','Đến tháng'),
            'from_month' => Yii::t('app','Từ tháng'),
            'type' => Yii::t('app','Loại báo cáo'),
            'site_id' => Yii::t('app','Nhà cung cấp dịch vụ'),
            'content_type' => \Yii::t('app', 'Loại nội dung'),

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
        $searchModel = new SubscriberSearch();
        $param['SubscriberSearch']['from_date'] =$from_date;
        $param['SubscriberSearch']['to_date'] =$to_date;

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
        $searchModel = new SubscriberSearch();
        $param['SubscriberSearch']['from_date'] =$from_date;
        $param['SubscriberSearch']['to_date'] =$to_date;

        $dataProvider = $searchModel->searchAll($param);
        return  $dataProvider;

    }
    public function generateDetailReport($rawData,$dateFormat = 'd/m/Y H:i:s'){
        $dataRow = [];
        //label header
        $sttLabel = Yii::t('app','STT');
        $dateLabel = Yii::t('app','Thời gian đăng ký');
        $total_via_site_label = Yii::t('app','Tên tài khoản');
        $time_android  = Yii::t('app','Thời gian đăng nhập Android lần cuối');
        $time_ios = Yii::t('app','Thời gian đăng nhập IOS lần cuối');
        $full_name = Yii::t('app','Tên người dùng');
        $sex = Yii::t('app','Giới tính');
        $time_website = Yii::t('app','Thời gian đăng nhập Website lần cuối');
        $address = Yii::t('app','Địa chỉ');
        if(!empty($rawData)){
            $i=0;
            foreach ($rawData as $raw){
                $row[$sttLabel] = ++$i;
                $row[$dateLabel] = date($dateFormat,$raw['created_at']);
                $row[$total_via_site_label] = $raw['username'];
                /** @var  $subscriber_ios SubscriberActivity */
                /** @var  $subscriber_android SubscriberActivity */
                /** @var  $subscriber_web SubscriberActivity */
                $subscriber_ios = SubscriberActivity::find()->andWhere(['subscriber_id'=>$raw['id']])->andWhere(['channel'=>SubscriberActivity::CHANNEL_IOS])->orderBy(['id'=>SORT_DESC])->one();
                $subscriber_android = SubscriberActivity::find()->andWhere(['subscriber_id'=>$raw['id']])->andWhere(['channel'=>SubscriberActivity::CHANNEL_APP])->orderBy(['id'=>SORT_DESC])->one();
                $subscriber_web = SubscriberActivity::find()->andWhere(['subscriber_id'=>$raw['id']])->andWhere(['channel'=>SubscriberActivity::CHANNEL_WEB])->orderBy(['id'=>SORT_DESC])->one();
                $row[$time_ios] = $subscriber_ios ? date($dateFormat,$subscriber_ios->created_at) : '';
                $row[$time_android] = $subscriber_android ? date($dateFormat,$subscriber_android->created_at) : '';
                $row[$time_website] = $subscriber_web ? date($dateFormat,$subscriber_web->created_at) : '';
                $row[$full_name] = $raw['full_name'];
                $row[$address] = $raw['address'];
                if($raw['sex']){
                    $row[$sex] = $raw['sex'] == 2 ? 'Nữ' : 'Nam';
                }else{
                    $row[$sex] = 'Chưa xác định';
                }
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

<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 10/26/2015
 * Time: 3:36 PM
 */

namespace backend\controllers;

use backend\models\MTForm;
use backend\models\ReportContentForm;
use backend\models\ReportContentHotForm;
use backend\models\ReportRevenuesForm;
use backend\models\ReportSubscriberActivityForm;
use backend\models\ReportSubscriberNumberForm;
use backend\models\ReportSubscriberServiceForm;
use backend\models\ReportTopupForm;
use backend\models\VoucherReportForm;
use common\components\ActionLogTracking;
use common\helpers\CUtils;
use common\models\Content;
use common\models\ReportSubscriberService;
use common\models\ReportVoucher;
use common\models\Category;
use common\models\Service;
use common\models\UserActivity;
use DateTime;
use Yii;
use yii\console\Controller;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

        public function actionSubscriberActivity()
    {
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['ReportSubscriberActivityForm']['site_id']) ? $param['ReportSubscriberActivityForm']['site_id'] : Yii::$app->params['site_id'];
        $content_type = isset($param['ReportSubscriberActivityForm']['content_type']) ? $param['ReportSubscriberActivityForm']['content_type'] : null;

        $from_date = isset($param['ReportSubscriberActivityForm']['from_date']) ? $param['ReportSubscriberActivityForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportSubscriberActivityForm']['to_date']) ? $param['ReportSubscriberActivityForm']['to_date'] : $to_date_default;

        $report = new ReportSubscriberActivityForm();
        $report->site_id = $site_id;
        $report->content_type = $content_type;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $dataProvider = $report->generateReport();
        $excelDataProvider = $report->generateDetailReport($dataProvider->getModels());
        return $this->render('subscriber-activity', [
            'report' => $report,
            'dataProvider' => $dataProvider,
            'site_id' => $site_id,
            'excelDataProvider' => $excelDataProvider
        ]);
    }

    public function actionContent()
    {
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['ReportContentForm']['site_id']) ? $param['ReportContentForm']['site_id'] :Yii::$app->params['site_id'] ;
        $cp_id = isset($param['ReportContentForm']['cp_id']) ? $param['ReportContentForm']['cp_id'] : null;
        $content_type = isset($param['ReportContentForm']['content_type']) ? $param['ReportContentForm']['content_type'] : Content::TYPE_VIDEO;
        $category_id = isset($param['ReportContentForm']['category_id'])?$param['ReportContentForm']['category_id']:Yii::$app->params['category_id'];
        $from_date = isset($param['ReportContentForm']['from_date']) ? $param['ReportContentForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportContentForm']['to_date']) ? $param['ReportContentForm']['to_date'] : $to_date_default;
        if($category_id == null || $category_id ==''){
            Yii::$app->session->setFlash('error', Yii::t('app','Thông tin không hợp lệ, Danh mục không được để trống'));
        }

        $report = new ReportContentForm();
        $report->site_id = $site_id;
        $report->cp_id = $cp_id;
        $report->content_type = $content_type;
        $report->category_id = $category_id;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $dataProvider = $report->generateReport();
        $dataCharts = $report->getData($dataProvider->getModels());
        return $this->render('content', [
            'report' => $report,
            'dataProvider' => $dataProvider,
            'content_type' => $content_type,
            'site_id' => $site_id,
            'cp_id'=>$cp_id,
            'category_id' => $category_id,
            'dataCharts'=> $dataCharts,
        ]);
    }

    public function actionContentHot()
    {
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['ReportContentHotForm']['site_id']) ? $param['ReportContentHotForm']['site_id'] : Yii::$app->params['site_id'];
        $cp_id = isset($param['ReportContentHotForm']['cp_id']) ? $param['ReportContentHotForm']['cp_id'] : null;
        $content_type = isset($param['ReportContentHotForm']['content_type']) ? $param['ReportContentHotForm']['content_type'] : null;
        $selectedCats = isset($param['ReportContentHotForm']['categoryIds']) ? explode(',', $param['ReportContentHotForm']['categoryIds']) : [];
        $categoryIds = isset($param['ReportContentHotForm']['categoryIds'])?$param['ReportContentHotForm']['categoryIds']:null;
        $from_date = isset($param['ReportContentHotForm']['from_date']) ? $param['ReportContentHotForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportContentHotForm']['to_date']) ? $param['ReportContentHotForm']['to_date'] : $to_date_default;
        $top = isset($param['ReportContentHotForm']['top']) ? $param['ReportContentHotForm']['top'] : 10;

        $report = new ReportContentHotForm();
        $report->site_id = $site_id;
        $report->cp_id = $cp_id;
        $report->content_type = $content_type;
        $report->categoryIds = $categoryIds;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $report->top = $top;
        $dataProvider = $report->generateReport();
        $dataProviderEx = $report->generateExport();

        $pagination = new Pagination(['totalCount' => $dataProviderEx->getTotalCount(), 'pageSize'=>20]);

        return $this->render('content-hot', [
            'report' => $report,
            'dataProvider' => $dataProvider,
            'content_type' => $content_type,
            'site_id' => $site_id,
            'cp_id'=>$cp_id,
            'selectedCats' => $selectedCats,
            'top'=>$top,
            'pagination' => $pagination,
            'dataProviderEx'=>$dataProviderEx,
        ]);
    }


    public function actionRevenues()
    {
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['ReportRevenuesForm']['site_id']) ? $param['ReportRevenuesForm']['site_id'] : Yii::$app->params['site_id'];
        $cp_id = isset($param['ReportRevenuesForm']['cp_id']) ? $param['ReportRevenuesForm']['cp_id'] : Yii::$app->params['cp_id'];
        $service_id = isset($param['ReportRevenuesForm']['service_id']) ? $param['ReportRevenuesForm']['service_id'] : null;
        $white_list = isset($param['ReportRevenuesForm']['white_list']) ? $param['ReportRevenuesForm']['white_list'] : null;
        $from_date = isset($param['ReportRevenuesForm']['from_date']) ? $param['ReportRevenuesForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportRevenuesForm']['to_date']) ? $param['ReportRevenuesForm']['to_date'] : $to_date_default;

        $report = new ReportRevenuesForm();
        $report->site_id = $site_id;
        $report->cp_id = $cp_id;
        $report->service_id = $service_id;
        $report->white_list = $white_list;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $dataProvider = $report->generateReport();
        $excelDataProvider = $report->generateDetailReport();
        $dataCharts = $report->getData($dataProvider->getModels());
        return $this->render('revenues', [
            'report' => $report,
            'dataProvider' => $dataProvider,
            'site_id' => $site_id,
            'cp_id' => $cp_id,
            'service_id' => $service_id,
            'white_list'=>$white_list,
            'excelDataProvider' => $excelDataProvider,
            'dataCharts'=> $dataCharts,
        ]);
    }

    public function actionFindServiceBySite() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $site_id = $parents[0];
                $cp_id=$parents[1];
                $out  = Service::findServiceBySiteAndCP($site_id,$cp_id);
                if(count($out)<=0){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                Yii::info($out);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionFindCategoryBySiteContent() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $site_id = $parents[0];
                $content_type = $parents[1];
                if(!$site_id){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                $items  = Category::findCategoryBySiteContent($site_id,$content_type);
                foreach($items as $item){
                    $item->display_name = str_pad($item->order_number,3,0,STR_PAD_LEFT).'-'.$item->path_name;
                }
                $out = $items;

//                $out  = Service::findServiceBySite($site_id);
                if(count($out)<=0){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    //add by tp report voucher

    public function actionVoucherReport(){
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['VoucherReportForm']['site_id']) ? $param['VoucherReportForm']['site_id'] : Yii::$app->params['site_id'];
        $from_date = isset($param['VoucherReportForm']['from_date']) ? $param['VoucherReportForm']['from_date'] : $from_date_default;
        $to_date = isset($param['VoucherReportForm']['to_date']) ? $param['VoucherReportForm']['to_date'] : $to_date_default;

        $report = new VoucherReportForm();
        $report->site_id = $site_id;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        // kiem tra ngay
        $dataProvider = $report->generateReport($site_id);
        $started = strtotime(DateTime::createFromFormat("d/m/Y", $from_date)->setTime(0, 0)->format('Y-m-d H:i:s'));
        $finished = strtotime(DateTime::createFromFormat("d/m/Y", $to_date)->setTime(0, 0)->format('Y-m-d H:i:s'));
        if ($finished < $started) {
            Yii::$app->session->setFlash('error', Yii::t('app','Ngày kết thúc tìm kiếm không được nhỏ hơn ngày bắt đầu tìm kiếm'));
        }
        return $this->render('voucher-report',[
            'report' => $report,
            'dataProvider' => $dataProvider,
            'site_id' => $site_id,
        ]);
    }

    public function actionMt(){
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['MTForm']['site_id']) ? $param['MTForm']['site_id'] : Yii::getAlias('@default_site_id');
        $from_date = isset($param['MTForm']['from_date']) ? $param['MTForm']['from_date'] : $from_date_default;
        $to_date = isset($param['MTForm']['to_date']) ? $param['MTForm']['to_date'] : $to_date_default;
        $msisdn = isset($param['MTForm']['msisdn']) ? $param['MTForm']['msisdn'] : '';
//        echo "<pre>"; print_r($msisdn);die();
        $report = new MTForm();
        $report->site_id = $site_id;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $report->msisdn = $msisdn;
        $dataProvider = $report->generateReport($site_id);
        // kiểm tra ngày
        $started = strtotime(DateTime::createFromFormat("d/m/Y", $from_date)->setTime(0, 0)->format('Y-m-d H:i:s'));
        $finished = strtotime(DateTime::createFromFormat("d/m/Y", $to_date)->setTime(0, 0)->format('Y-m-d H:i:s'));
        if ($finished < $started) {
            Yii::$app->session->setFlash('error', Yii::t('app','Ngày kết thúc tìm kiếm không được nhỏ hơn ngày bắt đầu tìm kiếm'));
        }
        return $this->render('mt-report',[
            'report' => $report,
            'dataProvider' => $dataProvider,
            'site_id' => $site_id,
        ]);
    }


    
    public function actionReportServiceSubscriber(){
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['ReportSubscriberServiceForm']['site_id']) ? $param['ReportSubscriberServiceForm']['site_id'] : Yii::$app->params['site_id'];
        $cp_id = isset($param['ReportSubscriberServiceForm']['cp_id']) ? $param['ReportSubscriberServiceForm']['cp_id'] : Yii::$app->params['cp_id'];
        $from_date = isset($param['ReportSubscriberServiceForm']['from_date']) ? $param['ReportSubscriberServiceForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportSubscriberServiceForm']['to_date']) ? $param['ReportSubscriberServiceForm']['to_date'] : $to_date_default;
        $service_id = isset($param['ReportSubscriberServiceForm']['service_id']) ? $param['ReportSubscriberServiceForm']['service_id'] : null;
        $white_list = isset($param['ReportSubscriberServiceForm']['white_list']) ? $param['ReportSubscriberServiceForm']['white_list'] : null;

        $report = new ReportSubscriberServiceForm();
        $report->site_id = $site_id;
        $report->cp_id = $cp_id;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $report->service_id = $service_id;
        $report->white_list = $white_list;
        $arrayProvider = $report->generateReport();
        $started = strtotime(DateTime::createFromFormat("d/m/Y", $from_date)->setTime(0, 0)->format('Y-m-d H:i:s'));
        $finished = strtotime(DateTime::createFromFormat("d/m/Y", $to_date)->setTime(0, 0)->format('Y-m-d H:i:s'));
        if ($finished < $started) {
            Yii::$app->session->setFlash('error', Yii::t('app','Ngày kết thúc tìm kiếm không được nhỏ hơn ngày bắt đầu tìm kiếm'));
        }
        return $this->render('report-subscriber-service',[
            'report' => $report,
            'dataProvider' => $arrayProvider[0],
            'dataProviderDetail' => $arrayProvider[1],
            'site_id' => $site_id,
            'cp_id'=>$cp_id
        ]);
    }
    
	/**
	 * Bao cao nap tien
	 * @return string
	 */
    public function actionTopup()
    {
    	$param = Yii::$app->request->queryParams;
    	$to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
    	$from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');
    
    	$site_id = isset($param['ReportTopupForm']['site_id']) ? $param['ReportTopupForm']['site_id'] : Yii::$app->params['site_id'];
    	$white_list = isset($param['ReportTopupForm']['white_list']) ? $param['ReportTopupForm']['white_list'] : null;
    	$channel = isset($param['ReportTopupForm']['channel']) ? $param['ReportTopupForm']['channel'] : null;
    	$from_date = isset($param['ReportTopupForm']['from_date']) ? $param['ReportTopupForm']['from_date'] : $from_date_default;
    	$to_date = isset($param['ReportTopupForm']['to_date']) ? $param['ReportTopupForm']['to_date'] : $to_date_default;
    	
    	$report = new ReportTopupForm();
    	$report->site_id = $site_id;
        $report->white_list = $white_list;
    	$report->channel = $channel;
    	$report->from_date = $from_date;
    	$report->to_date = $to_date;
    	$dataProvider = $report->generateReport();
    	$exportData = $report->getExcelData();
    
    	return $this->render('topup', [
    			'report' => $report,
    			'dataProvider' => $dataProvider,
    			'exportData' => $exportData,
    	]);
    }

    public function actionSubscriberNumber(){
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');

        $site_id = isset($param['ReportSubscriberNumberForm']['site_id']) ? $param['ReportSubscriberNumberForm']['site_id'] : Yii::$app->params['site_id'];
        $city = isset($param['ReportSubscriberNumberForm']['city']) ? $param['ReportSubscriberNumberForm']['city'] : null;
        $from_date = isset($param['ReportSubscriberNumberForm']['from_date']) ? $param['ReportSubscriberNumberForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportSubscriberNumberForm']['to_date']) ? $param['ReportSubscriberNumberForm']['to_date'] : $to_date_default;
        $report = new ReportSubscriberNumberForm();
        $report->site_id = $site_id;
        $report->city = $city;
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $arrayProvider = $report->generateReportSP();

        return $this->render('subscriber-number', [
            'report' => $report,
            'dataProvider' => $arrayProvider[0],
            'dataProviderDetail' => $arrayProvider[1],
            'site_id' => $site_id
        ]);
    }

    public function actionFindDealerBySite(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
               $site_id = $parents[0];
                if(!$site_id){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                $data  = CUtils::getCitySubscriberRegister($site_id);
                foreach($data as $key=>$value){
                    $row['id'] = $key;
                    $row['name'] = $value;
                    $out[] = $row;
                }
                if(count($out)<=0){
                    echo Json::encode(['output'=>array(), 'selected'=>'']);
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionFindCategoryByTypeAndSite(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $site_id = $parents[1];
                $type = $parents[0];
                $datas  = Category::findCategoryByTypeAndSite($site_id,$type);
                foreach ($datas as $category) {
                    $patents =explode("/", $category->path);
                    $name="";
                    $i=1;
                    if(count($patents)<=1){
                        $dataList['id'] = $category->id;
                        $dataList['name'] = $category->display_name;
                        $out[]=$dataList;
                    }
                    else{
                        foreach($patents as $item)
                        {
                            if($i == count($patents)){
                                $name= $name.$category->display_name;
                            }else{
                                $name=$name."|--";
                            }
                            $i++;
                        }
                        $dataList['id'] = $category->id;
                        $dataList['name'] = $name;
                        $out[]=$dataList;
                    }
                }
                Yii::info($out);
                if(count($out)<=0){
                    echo Json::encode(['output'=>'', 'selected'=>'']);
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
} 
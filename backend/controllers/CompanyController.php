<?php

namespace backend\controllers;

use api\models\CompanyNews;
use common\models\CompanyProfile;
use PHPExcel_IOFactory;
use Yii;
use common\models\Company;
use common\models\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
//        $model->setScenario('admin_create_update');
        if ($model->load(Yii::$app->request->post())) {
            $existCompany = Company::find()->andWhere(['username'=>strtolower($model->username)])->one();
            if($existCompany){
                Yii::$app->session->setFlash('error',"Username công ty đã tồn tại");
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file->extension;
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@news_image') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($file->saveAs($tmp . $file_name)) {
                    $model->file = $file_name;
                }
            }
            $model->username = strtolower($model->username);
            $model->save();
            $fileCompanyProfile = UploadedFile::getInstance($model, 'file_company_file');
            if ($fileCompanyProfile) {
                $file_name = uniqid() . time() . '.' . $fileCompanyProfile->extension;
                if ($fileCompanyProfile->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
                    $model->file_company_file = $file_name;
                    $model->save();
                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $first = 0;
                    if (sizeof($sheetData) > 0) {
                        foreach ($sheetData as $row) {
                            $first ++ ;
                            if($first > 3){
                                $companyProfile = new CompanyProfile();
                                $companyProfile->ma = $row['B'];
                                $companyProfile->cmnd = $row['C'];
                                $companyProfile->ten = $row['D'];
                                $companyProfile->ho = $row['E'];
                                $companyProfile->gioi_tinh = $row['F'];
                                $companyProfile->thon_lang = $row['G'];
                                $companyProfile->huyen = $row['H'];
                                $companyProfile->thanh_pho = $row['I'];
                                $companyProfile->sdt = $row['J'];
                                $companyProfile->email = $row['K'];
                                $companyProfile->nam_sinh = $row['L'];
                                $companyProfile->so_giay_to_chung_nhan = $row['M'];
                                $companyProfile->vi_do_gps = $row['N'];
                                $companyProfile->kinh_do_gps = $row['O'];
                                $companyProfile->ten_nguoi_dung = $row['P'];
                                $companyProfile->loai_ca_phe = $row['Q'];
                                $companyProfile->tong_san_luong_nam_nay = $row['R'];
                                $companyProfile->tong_san_luong_nam_ngoai = $row['S'];
                                $companyProfile->san_luong_ban_giao_nam_ngoai = $row['T'];
                                $companyProfile->san_luong_ban_giao_2_nam_truoc = $row['U'];
                                $companyProfile->san_luong_ban_giao_3_nam_truoc = $row['V'];
                                $companyProfile->nguoi_cmnd = $row['W'];
                                $companyProfile->nguoi_ten = $row['X'];
                                $companyProfile->nguoi_ho = $row['Y'];
                                $companyProfile->nguoi_gioi_tinh = $row['Z'];
                                $companyProfile->nguoi_email = $row['AA'];
                                $companyProfile->nguoi_sdt = $row['AB'];
                                $companyProfile->thanh_vien_nhom = $row['AC'];
                                $companyProfile->chung_nhan_tu_nam = $row['AD'];
                                $companyProfile->chuong_trinh_chung_nhan_khac = $row['AE'];
                                $companyProfile->chung_nhan_khac = $row['AF'];
                                $companyProfile->cmnd_thanh_tra = $row['AG'];
                                $companyProfile->hoten_thanh_tra = $row['AH'];
                                $companyProfile->nam_thanh_tra = $row['AI'];
                                $companyProfile->thang_thanh_tra = $row['AJ'];
                                $companyProfile->ngay_thanh_tra = $row['AK'];
                                $companyProfile->sl_cong_nhan_thoi_vu = $row['AL'];
                                $companyProfile->sl_cong_nhan_dai_han = $row['AM'];
                                $companyProfile->tong_so_vuon_ca_phe = $row['AN'];
                                $companyProfile->tong_so_dien_tich_chung_nhan = $row['AO'];
                                $companyProfile->tong_so_dien_tich_cac_vuon = $row['AP'];
                                $companyProfile->id_number = $row['AQ'];
                                $companyProfile->id_company = $model->id;
                                $companyProfile->save(false);
                            }
                        }
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Thêm mới công ty thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldFile = $model->file;
        if ($model->load(Yii::$app->request->post())) {
            $existCompany = Company::find()->andWhere(['username'=>strtolower($model->username)])->andWhere(['<>','id',$model->id])->one();
            if($existCompany){
                Yii::$app->session->setFlash('error',"Username công ty đã tồn tại");
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file->extension;
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@news_image') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($file->saveAs($tmp . $file_name)) {
                    $model->file = $file_name;
                }
            } else {
                $model->file = $oldFile;
            }
            $model->save();

            $fileCompanyProfile = UploadedFile::getInstance($model, 'file_company_file');
            if ($fileCompanyProfile) {
                $comProfile = CompanyProfile::find()->andWhere(['id_company'=>$model->id])->all();
                foreach ($comProfile as $item){
                    $item->delete();
                }
                $file_name = uniqid() . time() . '.' . $fileCompanyProfile->extension;
                if ($fileCompanyProfile->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
                    $model->file_company_file = $file_name;
                    $model->save();
                    $message = shell_exec("nohup  ./upload_company_profile.sh $model->id > /dev/null 2>&1 &");
//                    ini_set('memory_limit', '-1');
//                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
//                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $first = 0;
//                    if (sizeof($sheetData) > 0) {
//                        foreach ($sheetData as $row) {
//                            $first ++ ;
//                            if($first > 3){
//                                $companyProfile = new CompanyProfile();
//                                var_dump($row['B']);exit;
//                                $companyProfile->ma = $row['B'];
//                                $companyProfile->cmnd = $row['C'];
//                                $companyProfile->ten = $row['D'];
//                                $companyProfile->ho = $row['E'];
//                                $companyProfile->gioi_tinh = $row['F'];
//                                $companyProfile->thon_lang = $row['G'];
//                                $companyProfile->huyen = $row['H'];
//                                $companyProfile->thanh_pho = $row['I'];
//                                $companyProfile->sdt = $row['J'];
//                                $companyProfile->email = $row['K'];
//                                $companyProfile->nam_sinh = $row['L'];
//                                $companyProfile->so_giay_to_chung_nhan = $row['M'];
//                                $companyProfile->vi_do_gps = $row['N'];
//                                $companyProfile->kinh_do_gps = $row['O'];
//                                $companyProfile->ten_nguoi_dung = $row['P'];
//                                $companyProfile->loai_ca_phe = $row['Q'];
//                                $companyProfile->tong_san_luong_nam_nay = $row['R'];
//                                $companyProfile->tong_san_luong_nam_ngoai = $row['S'];
//                                $companyProfile->san_luong_ban_giao_nam_ngoai = $row['T'];
//                                $companyProfile->san_luong_ban_giao_2_nam_truoc = $row['U'];
//                                $companyProfile->san_luong_ban_giao_3_nam_truoc = $row['V'];
//                                $companyProfile->nguoi_cmnd = $row['W'];
//                                $companyProfile->nguoi_ten = $row['X'];
//                                $companyProfile->nguoi_ho = $row['Y'];
//                                $companyProfile->nguoi_gioi_tinh = $row['Z'];
//                                $companyProfile->nguoi_email = $row['AA'];
//                                $companyProfile->nguoi_sdt = $row['AB'];
//                                $companyProfile->thanh_vien_nhom = $row['AC'];
//                                $companyProfile->chung_nhan_tu_nam = $row['AD'];
//                                $companyProfile->chuong_trinh_chung_nhan_khac = $row['AE'];
//                                $companyProfile->chung_nhan_khac = $row['AF'];
//                                $companyProfile->cmnd_thanh_tra = $row['AG'];
//                                $companyProfile->hoten_thanh_tra = $row['AH'];
//                                $companyProfile->nam_thanh_tra = $row['AI'];
//                                $companyProfile->thang_thanh_tra = $row['AJ'];
//                                $companyProfile->ngay_thanh_tra = $row['AK'];
//                                $companyProfile->sl_cong_nhan_thoi_vu = $row['AL'];
//                                $companyProfile->sl_cong_nhan_dai_han = $row['AM'];
//                                $companyProfile->tong_so_vuon_ca_phe = $row['AN'];
//                                $companyProfile->tong_so_dien_tich_chung_nhan = $row['AO'];
//                                $companyProfile->tong_so_dien_tich_cac_vuon = $row['AP'];
////                                $companyProfile->id_number = $row['AQ'];
//                                $companyProfile->id_company = $model->id;
////                                $companyProfile->save(false);
//                            }
//                        }
//                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Cập nhật công ty thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $company = CompanyProfile::find()
            ->andWhere(['id_company'=>$id])->all();
        foreach ($company as $item){
            /** @var $item CompanyProfile */
            $item->delete();
        }

        Yii::$app->session->setFlash('success', 'Xóa công ty thành công');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

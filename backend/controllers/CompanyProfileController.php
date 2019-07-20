<?php

namespace backend\controllers;

use PHPExcel_IOFactory;
use Yii;
use common\models\CompanyProfile;
use common\models\CompanyProfileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompanyProfileController implements the CRUD actions for CompanyProfile model.
 */
class CompanyProfileController extends Controller
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
     * Lists all CompanyProfile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanyProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CompanyProfile model.
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
     * Creates a new CompanyProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CompanyProfile();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $first = 0;
                    if (sizeof($sheetData) > 0) {
                        foreach ($sheetData as $row) {
                            $first ++ ;
                            if($first > 3){
                                $model = new CompanyProfile();
                                $model->ma = $row['B'];
                                $model->cmnd = $row['C'];
                                $model->ten = $row['D'];
                                $model->ho = $row['E'];
                                $model->gioi_tinh = $row['F'];
                                $model->thon_lang = $row['G'];
                                $model->huyen = $row['H'];
                                $model->thanh_pho = $row['I'];
                                $model->sdt = $row['J'];
                                $model->email = $row['K'];
                                $model->nam_sinh = $row['L'];
                                $model->so_giay_to_chung_nhan = $row['M'];
                                $model->vi_do_gps = $row['N'];
                                $model->kinh_do_gps = $row['O'];
                                $model->ten_nguoi_dung = $row['P'];
                                $model->loai_ca_phe = $row['Q'];
                                $model->tong_san_luong_nam_nay = $row['R'];
                                $model->tong_san_luong_nam_ngoai = $row['S'];
                                $model->san_luong_ban_giao_nam_ngoai = $row['T'];
                                $model->san_luong_ban_giao_2_nam_truoc = $row['U'];
                                $model->san_luong_ban_giao_3_nam_truoc = $row['V'];
                                $model->nguoi_cmnd = $row['W'];
                                $model->nguoi_ten = $row['X'];
                                $model->nguoi_ho = $row['Y'];
                                $model->nguoi_gioi_tinh = $row['Z'];
                                $model->nguoi_email = $row['AA'];
                                $model->nguoi_sdt = $row['AB'];
                                $model->thanh_vien_nhom = $row['AC'];
                                $model->chung_nhan_tu_nam = $row['AD'];
                                $model->chuong_trinh_chung_nhan_khac = $row['AE'];
                                $model->chung_nhan_khac = $row['AF'];
                                $model->cmnd_thanh_tra = $row['AG'];
                                $model->hoten_thanh_tra = $row['AH'];
                                $model->nam_thanh_tra = $row['AI'];
                                $model->thang_thanh_tra = $row['AJ'];
                                $model->ngay_thanh_tra = $row['AK'];
                                $model->sl_cong_nhan_thoi_vu = $row['AL'];
                                $model->sl_cong_nhan_dai_han = $row['AM'];
                                $model->tong_so_vuon_ca_phe = $row['AN'];
                                $model->tong_so_dien_tich_chung_nhan = $row['AO'];
                                $model->tong_so_dien_tich_cac_vuon = $row['AP'];
                                $model->id_number = $row['AQ'];
                                $model->save();
                            }
                        }
                    }
                }
            }
            Yii::$app->session->setFlash('success','Import thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CompanyProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CompanyProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CompanyProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyProfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

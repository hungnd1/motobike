<?php

namespace backend\controllers;

use common\models\ImportDeviceForm;
use common\models\LogData;
use common\models\LogDataSearch;
use PHPExcel_IOFactory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * LogDataController implements the CRUD actions for LogData model.
 */
class LogDataController extends Controller
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
     * Lists all LogData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LogData model.
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
     * Creates a new LogData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LogData();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LogData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LogData model.
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
     * Finds the LogData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LogData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LogData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport()
    {
        $model = new ImportDeviceForm();
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'uploadedFile');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $sheetData1 = $objPHPExcel->getActiveSheet();
                    if (sizeof($sheetData) > 0) {
                        $content = '';
                        $idx = 0;
                        $row_a = $row_b = $row_c = $row_d = $row_e = $row_f = $row_g = $row_h = $row_i = $row_j = $row_k = $row_l =
                        $row_m = $row_n = $row_o = $row_p = $row_q = $row_r = $row_s = $row_t = $row_u = $row_v = $row_w = $row_x =
                        $row_y = $row_z = $row_aa = $row_ab = $row_ac = $row_ad = $row_ae = $row_af = $row_ag = $row_ah = $row_ai =
                        $row_aj = $row_ak = $row_al = $row_am = $row_an = $row_ao = $row_ap
                            = $row_aq = $row_ar = $row_as = $row_at = $row_au = $row_av = $row_aw = $row_ax = $row_ay = $row_az = $row_ba
                            = $row_bb = $row_bc = $row_bd = $row_be = $row_bf = $row_bg = $row_bh = $row_bi = $row_bj = $row_bk = $row_bl
                            = $row_bm = $row_bn = $row_bo = $row_bp = $row_bq = $row_br = $row_bs = $row_bt = $row_bu = $row_bv = $row_bw
                            = $row_bx = $row_by = $row_bz = $row_ca = $row_cb = $row_cc = $row_cd = $row_ce = $row_cf = $row_cg = $row_ch
                            = $row_ci = $row_cj = $row_ck = $row_cl = $row_cm = $row_cn = $row_co = $row_cp = $row_cq = $row_cr = $row_cs
                            = $row_ct = $row_cu = $row_cv = $row_cw = $row_cx = $row_cy = $row_cz = $row_da = $row_db = $row_dc = $row_dd
                            = $row_de = $row_df = $row_dg = $row_dh = $row_di = $row_dj = $row_dk = $row_dl = $row_dm = $row_dn = $row_do
                            = $row_dp = $row_dq = $row_dr = $row_ds = $row_dt = $row_du = $row_dv = $row_dw = $row_dx = $row_dy = $row_dz
                            = $row_ea = $row_eb = $row_ec = $row_ed = $row_ee = $row_ef = $row_eg = $row_eh = $row_ei = $row_ej = $row_ek
                            = $row_el = $row_em = $row_en = $row_eo = $row_ep = $row_eq = $row_er = $row_es = $row_et = $row_eu = $row_ev
                            = $row_ew = $row_ex = $row_ey = $row_ez = $row_fa = $row_fb = $row_fc = $row_fd = $row_fe = $row_ff
                            = $row_fg = $row_fh = '';
                        foreach ($sheetData as $row) {
                            $idx++;
                            if (trim($row['B']) == 'Tên hiển thị') {
                                $row_a = '<h3>' . $row['A'] . '</h3>';
                                $row_b = '<h3>' . $row['B'] . '</h3>';
                                $row_c = '<h3>' . $row['C'] . '</h3>';
                                $row_d = '<h3>' . $row['D'] . '</h3>';
                                $row_e = '<h3>' . $row['E'] . '</h3>';
                                $row_f = '<h3>' . $row['F'] . '</h3>';
                                $row_g = '<h3>' . $row['G'] . '</h3>';
                                $row_h = '<h3>' . $row['H'] . '</h3>';
                                $row_i = '<h3>' . $row['I'] . '</h3>';
                                $row_j = '<h3>' . $row['J'] . '</h3>';
                                $row_k = '<h3>' . $row['K'] . '</h3>';
                                $row_l = '<h3>' . $row['L'] . '</h3>';
                                $row_m = '<h3>' . $row['M'] . '</h3>';
                                $row_n = '<h3>' . $row['N'] . '</h3>';
                                $row_o = '<h3>' . $row['O'] . '</h3>';
                                $row_p = '<h3>' . $row['P'] . '</h3>';
                                $row_q = '<h3>' . $row['Q'] . '</h3>';
                                $row_r = '<h3>' . $row['R'] . '</h3>';
                                $row_s = '<h3>' . $row['S'] . '</h3>';
                                $row_t = '<h3>' . $row['T'] . '</h3>';
                                $row_u = '<h3>' . $row['U'] . '</h3>';
                                $row_v = '<h3>' . $row['V'] . '</h3>';
                                $row_w = '<h3>' . $row['W'] . '</h3>';
                                $row_x = '<h3>' . $row['X'] . '</h3>';
                                $row_y = '<h3>' . $row['Y'] . '</h3>';
                                $row_z = '<h3>' . $row['Z'] . '</h3>';
                                $row_aa = '<h3>' . $row['AA'] . '</h3>';
                                $row_ab = '<h3>' . $row['AB'] . '</h3>';
                                $row_ac = '<h3>' . $row['AC'] . '</h3>';
                                $row_ad = '<h3>' . $row['AD'] . '</h3>';
                                $row_ae = '<h3>' . $row['AE'] . '</h3>';
                                $row_af = '<h3>' . $row['AF'] . '</h3>';
                                $row_ag = '<h3>' . $row['AG'] . '</h3>';
                                $row_ah = '<h3>' . $row['AH'] . '</h3>';
                                $row_ai = '<h3>' . $row['AI'] . '</h3>';
                                $row_aj = '<h3>' . $row['AJ'] . '</h3>';
                                $row_ak = '<h3>' . $row['AK'] . '</h3>';
                                $row_al = '<h3>' . $row['AL'] . '</h3>';
                                $row_am = '<h3>' . $row['AM'] . '</h3>';
                                $row_an = '<h3>' . $row['AN'] . '</h3>';
                                $row_ao = '<h3>' . $row['AO'] . '</h3>';
                                $row_ap = '<h3>' . $row['AP'] . '</h3>';
                                $row_aq = '<h3>' . $row['AQ'] . '</h3>';
                                $row_ar = '<h3>' . $row['AR'] . '</h3>';
                                $row_as = '<h3>' . $row['AS'] . '</h3>';
                                $row_at = '<h3>' . $row['AT'] . '</h3>';
                                $row_au = '<h3>' . $row['AU'] . '</h3>';
                                $row_av = '<h3>' . $row['AV'] . '</h3>';
                                $row_aw = '<h3>' . $row['AW'] . '</h3>';
                                $row_ax = '<h3>' . $row['AX'] . '</h3>';
                                $row_ay = '<h3>' . $row['AY'] . '</h3>';
                                $row_az = '<h3>' . $row['AZ'] . '</h3>';
                                $row_ba = '<h3>' . $row['BA'] . '</h3>';
                                $row_bb = '<h3>' . $row['BB'] . '</h3>';
                                $row_bc = '<h3>' . $row['BC'] . '</h3>';
                                $row_bd = '<h3>' . $row['BD'] . '</h3>';
                                $row_be = '<h3>' . $row['BE'] . '</h3>';
                                $row_bf = '<h3>' . $row['BF'] . '</h3>';
                                $row_bg = '<h3>' . $row['BG'] . '</h3>';
                                $row_bh = '<h3>' . $row['BH'] . '</h3>';
                                $row_bi = '<h3>' . $row['BI'] . '</h3>';
                                $row_bj = '<h3>' . $row['BJ'] . '</h3>';
                                $row_bk = '<h3>' . $row['BK'] . '</h3>';
                                $row_bl = '<h3>' . $row['BL'] . '</h3>';
                                $row_bm = '<h3>' . $row['BM'] . '</h3>';
                                $row_bn = '<h3>' . $row['BN'] . '</h3>';
                                $row_bo = '<h3>' . $row['BO'] . '</h3>';
                                $row_bp = '<h3>' . $row['BP'] . '</h3>';
                                $row_bq = '<h3>' . $row['BQ'] . '</h3>';
                                $row_br = '<h3>' . $row['BR'] . '</h3>';
                                $row_bs = '<h3>' . $row['BS'] . '</h3>';
                                $row_bt = '<h3>' . $row['BT'] . '</h3>';
                                $row_bu = '<h3>' . $row['BU'] . '</h3>';
                                $row_bv = '<h3>' . $row['BV'] . '</h3>';
                                $row_bw = '<h3>' . $row['BW'] . '</h3>';
                                $row_bx = '<h3>' . $row['BX'] . '</h3>';
                                $row_by = '<h3>' . $row['BY'] . '</h3>';
                                $row_bz = '<h3>' . $row['BZ'] . '</h3>';
                                $row_ca = '<h3>' . $row['CA'] . '</h3>';
                                $row_cb = '<h3>' . $row['CB'] . '</h3>';
                                $row_cc = '<h3>' . $row['CC'] . '</h3>';
                                $row_cd = '<h3>' . $row['CD'] . '</h3>';
                                $row_ce = '<h3>' . $row['CE'] . '</h3>';
                                $row_cf = '<h3>' . $row['CF'] . '</h3>';
                                $row_cg = '<h3>' . $row['CG'] . '</h3>';
                                $row_ch = '<h3>' . $row['CH'] . '</h3>';
                                $row_ci = '<h3>' . $row['CI'] . '</h3>';
                                $row_cj = '<h3>' . $row['CJ'] . '</h3>';
                                $row_ck = '<h3>' . $row['CK'] . '</h3>';
                                $row_cl = '<h3>' . $row['CL'] . '</h3>';
                                $row_cm = '<h3>' . $row['CM'] . '</h3>';
                                $row_cn = '<h3>' . $row['CN'] . '</h3>';
                                $row_co = '<h3>' . $row['CO'] . '</h3>';
                                $row_cp = '<h3>' . $row['CP'] . '</h3>';
                                $row_cq = '<h3>' . $row['CQ'] . '</h3>';
                                $row_cr = '<h3>' . $row['CR'] . '</h3>';
                                $row_cs = '<h3>' . $row['CS'] . '</h3>';
                                $row_ct = '<h3>' . $row['CT'] . '</h3>';
                                $row_cu = '<h3>' . $row['CU'] . '</h3>';
                                $row_cv = '<h3>' . $row['CV'] . '</h3>';
                                $row_cw = '<h3>' . $row['CW'] . '</h3>';
                                $row_cx = '<h3>' . $row['CX'] . '</h3>';
                                $row_cy = '<h3>' . $row['CY'] . '</h3>';
                                $row_cz = '<h3>' . $row['CZ'] . '</h3>';
                                $row_da = '<h3>' . $row['DA'] . '</h3>';
                                $row_db = '<h3>' . $row['DB'] . '</h3>';
                                $row_dc = '<h3>' . $row['DC'] . '</h3>';
                                $row_dd = '<h3>' . $row['DD'] . '</h3>';
                                $row_de = '<h3>' . $row['DE'] . '</h3>';
                                $row_df = '<h3>' . $row['DF'] . '</h3>';
                                $row_dg = '<h3>' . $row['DG'] . '</h3>';
                                $row_dh = '<h3>' . $row['DH'] . '</h3>';
                                $row_di = '<h3>' . $row['DI'] . '</h3>';
                                $row_dj = '<h3>' . $row['DJ'] . '</h3>';
                                $row_dk = '<h3>' . $row['DK'] . '</h3>';
                                $row_dl = '<h3>' . $row['DL'] . '</h3>';
                                $row_dm = '<h3>' . $row['DM'] . '</h3>';
                                $row_dn = '<h3>' . $row['DN'] . '</h3>';
                                $row_do = '<h3>' . $row['DO'] . '</h3>';
                                $row_dp = '<h3>' . $row['DP'] . '</h3>';
                                $row_dq = '<h3>' . $row['DQ'] . '</h3>';
                                $row_dr = '<h3>' . $row['DR'] . '</h3>';
                                $row_ds = '<h3>' . $row['DS'] . '</h3>';
//                                $row_dt = '<h3>' . $row['DT'] . '</h3>';
//                                $row_du = '<h3>' . $row['DU'] . '</h3>';
//                                $row_dv = '<h3>' . $row['DV'] . '</h3>';
//                                $row_dw = '<h3>' . $row['DW'] . '</h3>';
//                                $row_dx = '<h3>' . $row['DX'] . '</h3>';
//                                $row_dy = '<h3>' . $row['DY'] . '</h3>';
//                                $row_dz = '<h3>' . $row['DZ'] . '</h3>';
//                                $row_ea = '<h3>' . $row['EA'] . '</h3>';
//                                $row_eb = '<h3>' . $row['EB'] . '</h3>';
//                                $row_ec = '<h3>' . $row['EC'] . '</h3>';
//                                $row_ed = '<h3>' . $row['ED'] . '</h3>';
//                                $row_ee = '<h3>' . $row['EE'] . '</h3>';
//                                $row_ef = '<h3>' . $row['EF'] . '</h3>';
//                                $row_eg = '<h3>' . $row['EG'] . '</h3>';
//                                $row_eh = '<h3>' . $row['EH'] . '</h3>';
//                                $row_ei = '<h3>' . $row['EI'] . '</h3>';
//                                $row_ej = '<h3>' . $row['EJ'] . '</h3>';
//                                $row_ek = '<h3>' . $row['EK'] . '</h3>';
//                                $row_el = '<h3>' . $row['EL'] . '</h3>';
//                                $row_em = '<h3>' . $row['EM'] . '</h3>';
//                                $row_en = '<h3>' . $row['EN'] . '</h3>';
//                                $row_eo = '<h3>' . $row['EO'] . '</h3>';
//                                $row_ep = '<h3>' . $row['EP'] . '</h3>';
//                                $row_eq = '<h3>' . $row['EQ'] . '</h3>';
//                                $row_er = '<h3>' . $row['ER'] . '</h3>';
//                                $row_es = '<h3>' . $row['ES'] . '</h3>';
//                                $row_et = '<h3>' . $row['ET'] . '</h3>';
//                                $row_eu = '<h3>' . $row['EU'] . '</h3>';
//                                $row_ev = '<h3>' . $row['EV'] . '</h3>';
//                                $row_ew = '<h3>' . $row['EW'] . '</h3>';
//                                $row_ex = '<h3>' . $row['EX'] . '</h3>';
//                                $row_ey = '<h3>' . $row['EY'] . '</h3>';
//                                $row_ez = '<h3>' . $row['EZ'] . '</h3>';
//                                $row_fa = '<h3>' . $row['FA'] . '</h3>';
//                                $row_fb = '<h3>' . $row['FB'] . '</h3>';
//                                $row_fc = '<h3>' . $row['FC'] . '</h3>';
//                                $row_fd = '<h3>' . $row['FD'] . '</h3>';
//                                $row_fe = '<h3>' . $row['FE'] . '</h3>';
//                                $row_ff = '<h3>' . $row['FF'] . '</h3>';
//                                $row_fg = '<h3>' . $row['FG'] . '</h3>';
//                                $row_fh = '<h3>' . $row['FH'] . '</h3>';
                            } else {
//                                foreach ($sheetData1->getDrawingCollection() as $drawing) {
//
//                                }
//                                if(key($sheetData) == 'DI'){
//                                    var_dump(1);exit;
//                                }
//                                var_dump($row);exit;
                                $content = $row_a . '<p>' . $row['A'] . '</p>' . $row_c . '<p>' . $row['C'] . '</p>' . $row_d . '<p>' . $row['D'] . '</p>' .
                                    $row_e . '<p>' . $row['E'] . '</p>' . $row_f . '<p>' . $row['F'] . '</p>' . $row_g . '<p>' . $row['G'] . '</p>' . $row_h . '<p>' . $row['H'] . '</p>' .
                                    $row_i . '<p>' . $row['I'] . '</p>' . $row_j . '<p>' . $row['J'] . '</p>' . $row_k . '<p>' . $row['K'] . '</p>' . $row_l . '<p>' . $row['L'] . '</p>' .
                                    $row_m . '<p>' . $row['M'] . '</p>' . $row_n . '<p>' . $row['N'] . '</p>' . $row_o . '<p>' . $row['O'] . '</p>' . $row_p . '<p>' . $row['P'] . '</p>' .
                                    $row_q . '<p>' . $row['Q'] . '</p>' . $row_r . '<p>' . $row['R'] . '</p>' . $row_s . '<p>' . $row['S'] . '</p>' . $row_t . '<p>' . $row['T'] . '</p>' .
                                    $row_u . '<p>' . $row['U'] . '</p>' . $row_v . '<p>' . $row['V'] . '</p>' . $row_w . '<p>' . $row['W'] . '</p>' . $row_x . '<p>' . $row['X'] . '</p>' .
                                    $row_y . '<p>' . $row['Y'] . '</p>' . $row_z . '<p>' . $row['Z'] . '</p>' . $row_aa . '<p>' . $row['AA'] . '</p>' . $row_ab . '<p>' . $row['AB'] . '</p>' .
                                    $row_ac . '<p>' . $row['AC'] . '</p>' . $row_ad . '<p>' . $row['AD'] . '</p>' . $row_ae . '<p>' . $row['AE'] . '</p>' . $row_af . '<p>' . $row['AF'] . '</p>' .
                                    $row_ag . '<p>' . $row['AG'] . '</p>' . $row_ah . '<p>' . $row['AH'] . '</p>' . $row_ai . '<p>' . $row['AI'] . '</p>' . $row_aj . '<p>' . $row['AJ'] . '</p>' .
                                    $row_ak . '<p>' . $row['AK'] . '</p>' . $row_al . '<p>' . $row['AL'] . '</p>' . $row_am . '<p>' . $row['AM'] . '</p>' . $row_an . '<p>' . $row['AN'] . '</p>' .
                                    $row_ao . '<p>' . $row['AO'] . '</p>' . $row_ap . '<p>' . $row['AP'] . '</p>' . $row_aq . '<p>' . $row['AQ'] . '</p>' . $row_ar . '<p>' . $row['AR'] . '</p>' .
                                    $row_as . '<p>' . $row['AS'] . '</p>' . $row_at . '<p>' . $row['AT'] . '</p>' . $row_au . '<p>' . $row['AU'] . '</p>' . $row_av . '<p>' . $row['AV'] . '</p>' .
                                    $row_aw . '<p>' . $row['AW'] . '</p>' . $row_ax . '<p>' . $row['AX'] . '</p>' . $row_ay . '<p>' . $row['AY'] . '</p>' . $row_az . '<p>' . $row['AZ'] . '</p>' .
                                    $row_ba . '<p>' . $row['BA'] . '</p>' . $row_bb . '<p>' . $row['BB'] . '</p>' . $row_bc . '<p>' . $row['BC'] . '</p>' . $row_bd . '<p>' . $row['BD'] . '</p>' .
                                    $row_be . '<p>' . $row['BE'] . '</p>' . $row_bf . '<p>' . $row['BF'] . '</p>' . $row_bg . '<p>' . $row['BG'] . '</p>' . $row_bh . '<p>' . $row['BH'] . '</p>' .
                                    $row_bi . '<p>' . $row['BI'] . '</p>' . $row_bj . '<p>' . $row['BJ'] . '</p>' . $row_bk . '<p>' . $row['BK'] . '</p>' . $row_bl . '<p>' . $row['BL'] . '</p>' .
                                    $row_bm . '<p>' . $row['BM'] . '</p>' . $row_bn . '<p>' . $row['BN'] . '</p>' . $row_bo . '<p>' . $row['BO'] . '</p>' . $row_bp . '<p>' . $row['BP'] . '</p>' .
                                    $row_bq . '<p>' . $row['BQ'] . '</p>' . $row_br . '<p>' . $row['BR'] . '</p>' . $row_bd . '<p>' . $row['BS'] . '</p>' . $row_bt . '<p>' . $row['BT'] . '</p>' .
                                    $row_bu . '<p>' . $row['BU'] . '</p>' . $row_bv . '<p>' . $row['BV'] . '</p>' . $row_bw . '<p>' . $row['BW'] . '</p>' . $row_bx . '<p>' . $row['BX'] . '</p>' .
                                    $row_by . '<p>' . $row['BY'] . '</p>' . $row_bz . '<p>' . $row['BZ'] .
                                    $row_ca . '<p>' . $row['CA'] . '</p>' . $row_cb . '<p>' . $row['CB'] . '</p>' . $row_cc . '<p>' . $row['CC'] . '</p>' . $row_cd . '<p>' . $row['CD'] . '</p>' .
                                    $row_ce . '<p>' . $row['CE'] . '</p>' . $row_cf . '<p>' . $row['CF'] . '</p>' . $row_cg . '<p>' . $row['CG'] . '</p>' . $row_ch . '<p>' . $row['CH'] . '</p>' .
                                    $row_ci . '<p>' . $row['CI'] . '</p>' . $row_cj . '<p>' . $row['CJ'] . '</p>' . $row_ck . '<p>' . $row['CK'] . '</p>' . $row_cl . '<p>' . $row['CL'] . '</p>' .
                                    $row_cm . '<p>' . $row['CM'] . '</p>' . $row_cn . '<p>' . $row['CN'] . '</p>' . $row_co . '<p>' . $row['CO'] . '</p>' . $row_cp . '<p>' . $row['CP'] . '</p>' .
                                    $row_cq . '<p>' . $row['CQ'] . '</p>' . $row_cr . '<p>' . $row['CR'] . '</p>' . $row_cs . '<p>' . $row['CS'] . '</p>' . $row_ct . '<p>' . $row['CT'] . '</p>' .
                                    $row_cu . '<p>' . $row['CU'] . '</p>' . $row_cv . '<p>' . $row['CV'] . '</p>' . $row_cw . '<p>' . $row['CW'] . '</p>' . $row_cx . '<p>' . $row['CX'] . '</p>' .
                                    $row_cy . '<p>' . $row['CY'] . '</p>' . $row_cz . '<p>' . $row['CZ'] .
                                    $row_da . '<p>' . $row['DA'] . '</p>' . $row_db . '<p>' . $row['DB'] . '</p>' . $row_dc . '<p>' . $row['DC'] . '</p>' . $row_dd . '<p>' . $row['DD'] . '</p>' .
                                    $row_de . '<p>' . $row['DE'] . '</p>' . $row_df . '<p>' . $row['DF'] . '</p>' . $row_dg . '<p>' . $row['DG'] . '</p>' . $row_dh . '<p>' . $row['DH'] . '</p>' .
                                    $row_di . '<p>' . $row['DI'] . '</p>' . $row_dj . '<p>' . $row['DJ'] . '</p>' . $row_dk . '<p>' . $row['DK'] . '</p>' . $row_dl . '<p>' . $row['DL'] . '</p>' .
                                    $row_dm . '<p>' . $row['DM'] . '</p>' . $row_dn . '<p>' . $row['DN'] . '</p>' . $row_do . '<p>' . $row['DO'] . '</p>' . $row_dp . '<p>' . $row['DP'] . '</p>' .
                                    $row_dq . '<p>' . $row['DQ'] . '</p>' . $row_dr . '<p>' . $row['DR'] . '</p>' . $row_ds . '<p>' . $row['DS'] . '</p>';
//                                    . $row_dt . '<p>' . $row['DT'] . '</p>' .
//                                    $row_du . '<p>' . $row['DU'] . '</p>' . $row_dv . '<p>' . $row['DV'] . '</p>' . $row_dw . '<p>' . $row['DW'] . '</p>' . $row_dx . '<p>' . $row['DX'] . '</p>' .
//                                    $row_dy . '<p>' . $row['DY'] . '</p>' . $row_dz . '<p>' . $row['DZ'] .
//                                    $row_ea . '<p>' . $row['EA'] . '</p>' . $row_eb . '<p>' . $row['EB'] . '</p>' . $row_ec . '<p>' . $row['EC'] . '</p>' . $row_ed . '<p>' . $row['ED'] . '</p>' .
//                                    $row_ee . '<p>' . $row['EE'] . '</p>' . $row_ef . '<p>' . $row['EF'] . '</p>' . $row_eg . '<p>' . $row['EG'] . '</p>' . $row_eh . '<p>' . $row['EH'] . '</p>' .
//                                    $row_ei . '<p>' . $row['EI'] . '</p>' . $row_ej . '<p>' . $row['EJ'] . '</p>' . $row_ek . '<p>' . $row['EK'] . '</p>' . $row_el . '<p>' . $row['EL'] . '</p>' .
//                                    $row_em . '<p>' . $row['EM'] . '</p>' . $row_en . '<p>' . $row['EN'] . '</p>' . $row_eo . '<p>' . $row['EO'] . '</p>' . $row_ep . '<p>' . $row['EP'] . '</p>' .
//                                    $row_eq . '<p>' . $row['EQ'] . '</p>' . $row_er . '<p>' . $row['ER'] . '</p>' . $row_es . '<p>' . $row['ES'] . '</p>' . $row_et . '<p>' . $row['ET'] . '</p>' .
//                                    $row_eu . '<p>' . $row['EU'] . '</p>' . $row_ev . '<p>' . $row['EV'] . '</p>' . $row_ew . '<p>' . $row['EW'] . '</p>' . $row_ex . '<p>' . $row['EX'] . '</p>' .
//                                    $row_ey . '<p>' . $row['EY'] . '</p>' . $row_ez . '<p>' . $row['EZ'] .
//                                    $row_fa . '<p>' . $row['FA'] . '</p>' . $row_fb . '<p>' . $row['FB'] . '</p>' . $row_fc . '<p>' . $row['FC'] . '</p>' . $row_fd . '<p>' . $row['FD'] . '</p>' .
//                                    $row_fe . '<p>' . $row['FE'] . '</p>' . $row_ff . '<p>' . $row['FF'] . '</p>' . $row_fg . '<p>' . $row['FG'] . '</p>' . $row_fh . '<p>' . $row['FH'] . '</p>';
                                $log = new LogData();
                                $log->latitude = $row['DL'];
                                $log->longitude = $row['DM'];
                                $log->content = $this->getImportedValue(LogData::CONTENT, $content);
                                $log->save(false);
                            }

                        }

                        Yii::$app->getSession()->setFlash('success', Yii::t("app", "Đã import thành công"));
//                        return $this->actionIndex();
                        return $this->redirect(['index']);
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t("app", "Có lỗi xảy ra trong quá trình upload. Vui lòng thử lại"));
                $model = new ImportDeviceForm();
                return $this->render('import', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('import', [
                'model' => $model,
            ]);
        }
    }

//
    private function getImportedValue($attr, $value)
    {
        $value = trim($value);
        switch ($attr) {
            case LogData::CONTENT:
                return $value;
        }
        return $value;
    }

    public function actionMap()
    {
        return $this->render('map');
    }
}

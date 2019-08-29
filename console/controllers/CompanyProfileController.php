<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea92
 * Date: 8/29/2019
 * Time: 4:40 PM
 */

namespace console\controllers;


use common\models\Company;
use common\models\CompanyProfile;
use PHPExcel_IOFactory;
use Yii;
use yii\console\Controller;

class CompanyProfileController extends Controller
{
    public function actionUploadProfile($id)
    {
        /** @var Company $company */
        $company = Company::findOne($id);
        $local_file = Yii::$app->params['folder'] . 'backend/web/uploaded_excels/' . $company->file_company_file;
        ini_set('memory_limit', '-1');
        $objPHPExcel = PHPExcel_IOFactory::load($local_file);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $first = 0;
        if (sizeof($sheetData) > 0) {
            foreach ($sheetData as $row) {
                $first++;
                if ($first > 5) {
                    $companyProfile = new CompanyProfile();
                    if($row['C'] != '' && $row['D'] != '' && $row['F'] != ''){
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
//                                $companyProfile->id_number = $row['AQ'];
                        $companyProfile->id_company = $id;
                        $companyProfile->save(false);
                    }
                }
            }
        }
    }
}
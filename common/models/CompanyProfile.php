<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "company_profile".
 *
 * @property integer $id
 * @property string $ma
 * @property string $cmnd
 * @property string $ten
 * @property string $ho
 * @property string $gioi_tinh
 * @property string $thon_lang
 * @property string $huyen
 * @property string $thanh_pho
 * @property string $sdt
 * @property string $email
 * @property string $nam_sinh
 * @property string $so_giay_to_chung_nhan
 * @property string $vi_do_gps
 * @property string $kinh_do_gps
 * @property string $ten_nguoi_dung
 * @property string $loai_ca_phe
 * @property string $tong_san_luong_nam_nay
 * @property string $tong_san_luong_nam_ngoai
 * @property string $san_luong_ban_giao_nam_ngoai
 * @property string $san_luong_ban_giao_2_nam_truoc
 * @property string $san_luong_ban_giao_3_nam_truoc
 * @property string $nguoi_cmnd
 * @property string $nguoi_ten
 * @property string $nguoi_ho
 * @property string $nguoi_gioi_tinh
 * @property string $nguoi_email
 * @property string $nguoi_sdt
 * @property string $thanh_vien_nhom
 * @property string $chung_nhan_tu_nam
 * @property string $chuong_trinh_chung_nhan_khac
 * @property string $chung_nhan_khac
 * @property string $cmnd_thanh_tra
 * @property string $hoten_thanh_tra
 * @property string $nam_thanh_tra
 * @property string $thang_thanh_tra
 * @property string $sl_cong_nhan_thoi_vu
 * @property string $sl_cong_nhan_dai_han
 * @property string $tong_so_vuon_ca_phe
 * @property string $tong_so_dien_tich_chung_nhan
 * @property string $tong_so_dien_tich_cac_vuon
 * @property string $id_number
 * @property string $file
 * @property string $ngay_thanh_tra
 * @property integer $id_company
 */
class CompanyProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_company'], 'integer'],
            [['ma', 'cmnd', 'ten','ngay_thanh_tra', 'ho', 'gioi_tinh', 'sdt', 'email', 'nam_sinh', 'so_giay_to_chung_nhan', 'vi_do_gps', 'kinh_do_gps', 'loai_ca_phe', 'tong_san_luong_nam_nay', 'tong_san_luong_nam_ngoai', 'san_luong_ban_giao_nam_ngoai', 'san_luong_ban_giao_2_nam_truoc', 'san_luong_ban_giao_3_nam_truoc', 'nguoi_cmnd', 'nguoi_ten', 'nguoi_ho', 'nguoi_gioi_tinh', 'nguoi_email', 'nguoi_sdt', 'thanh_vien_nhom', 'chung_nhan_tu_nam', 'chuong_trinh_chung_nhan_khac', 'chung_nhan_khac', 'cmnd_thanh_tra', 'hoten_thanh_tra', 'nam_thanh_tra', 'thang_thanh_tra', 'sl_cong_nhan_thoi_vu', 'sl_cong_nhan_dai_han', 'tong_so_vuon_ca_phe', 'tong_so_dien_tich_chung_nhan', 'tong_so_dien_tich_cac_vuon', 'id_number'], 'string', 'max' => 255],
            [['thon_lang', 'huyen', 'thanh_pho', 'ten_nguoi_dung','file'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma' => 'Ma',
            'cmnd' => 'Cmnd',
            'ten' => 'Ten',
            'ho' => 'Ho',
            'gioi_tinh' => 'Gioi Tinh',
            'thon_lang' => 'Thon Lang',
            'huyen' => 'Huyen',
            'thanh_pho' => 'Thanh Pho',
            'sdt' => 'Sdt',
            'email' => 'Email',
            'nam_sinh' => 'Nam Sinh',
            'so_giay_to_chung_nhan' => 'So Giay To Chung Nhan',
            'vi_do_gps' => 'Vi Do Gps',
            'kinh_do_gps' => 'Kinh Do Gps',
            'ten_nguoi_dung' => 'Ten Nguoi Dung',
            'loai_ca_phe' => 'Loai Ca Phe',
            'tong_san_luong_nam_nay' => 'Tong San Luong Nam Nay',
            'tong_san_luong_nam_ngoai' => 'Tong San Luong Nam Ngoai',
            'san_luong_ban_giao_nam_ngoai' => 'San Luong Ban Giao Nam Ngoai',
            'san_luong_ban_giao_2_nam_truoc' => 'San Luong Ban Giao 2 Nam Truoc',
            'san_luong_ban_giao_3_nam_truoc' => 'San Luong Ban Giao 3 Nam Truoc',
            'nguoi_cmnd' => 'Nguoi Cmnd',
            'nguoi_ten' => 'Nguoi Ten',
            'nguoi_ho' => 'Nguoi Ho',
            'nguoi_gioi_tinh' => 'Nguoi Gioi Tinh',
            'nguoi_email' => 'Nguoi Email',
            'nguoi_sdt' => 'Nguoi Sdt',
            'thanh_vien_nhom' => 'Thanh Vien Nhom',
            'chung_nhan_tu_nam' => 'Chung Nhan Tu Nam',
            'chuong_trinh_chung_nhan_khac' => 'Chuong Trinh Chung Nhan Khac',
            'chung_nhan_khac' => 'Chung Nhan Khac',
            'cmnd_thanh_tra' => 'Cmnd Thanh Tra',
            'hoten_thanh_tra' => 'Hoten Thanh Tra',
            'nam_thanh_tra' => 'Nam Thanh Tra',
            'thang_thanh_tra' => 'Thang Thanh Tra',
            'sl_cong_nhan_thoi_vu' => 'Sl Cong Nhan Thoi Vu',
            'sl_cong_nhan_dai_han' => 'Sl Cong Nhan Dai Han',
            'tong_so_vuon_ca_phe' => 'Tong So Vuon Ca Phe',
            'tong_so_dien_tich_chung_nhan' => 'Tong So Dien Tich Chung Nhan',
            'tong_so_dien_tich_cac_vuon' => 'Tong So Dien Tich Cac Vuon',
            'id_number' => 'Mã nông dân',
            'id_company' => 'Công ty',
            'file' => 'File'
        ];
    }
    public static function getTemplateFilePrice() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/DS nong dan (vi du).xlsx';
    }

    public function getImageLink()
    {
        return $this->file ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->file, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}

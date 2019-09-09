<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form_analyst".
 *
 * @property integer $id
 * @property string $tenChuVuon
 * @property string $cmnd
 * @property string $dienTich
 * @property string $congLamCoCong
 * @property string $congLamCoDong
 * @property string $congTaoHinhCong
 * @property string $congTaoHinhDong
 * @property string $congBonPhanCong
 * @property string $congBonPhanDong
 * @property string $congThuHaiCong
 * @property string $congThuHaiDong
 * @property string $congSoCheCong
 * @property string $congSoCheDong
 * @property string $congTuoiCong
 * @property string $congTuoiDong
 * @property string $congPhunThuocCong
 * @property string $congPhunThuocDong
 * @property string $congKhacCong
 * @property string $congKhacDong
 * @property string $thuocSauCong
 * @property string $thuocsauDong
 * @property string $thuocBenhCong
 * @property string $thuocBenhDong
 * @property string $phanBonLaCong
 * @property string $phanBonLaDong
 * @property string $phanHuuCoCong
 * @property string $phanHuuCoDong
 * @property string $voiNongNghiepCong
 * @property string $voiNongNghiepDong
 * @property string $phanViSinhCong
 * @property string $phanViSinhDong
 * @property string $phanDamSaCong
 * @property string $phanDamSaDong
 * @property string $phanDamUreCong
 * @property string $phanDamUreDong
 * @property string $phanLanCong
 * @property string $phanLanDong
 * @property string $phanKaliCong
 * @property string $phanKaliDong
 * @property string $phanHonHop1Cong
 * @property string $phanHonHop1Dong
 * @property string $phanHonHop1N
 * @property string $phanHonHop1P
 * @property string $phanHonHop1K
 * @property string $phanHonHop2Cong
 * @property string $phanHonHop2Dong
 * @property string $phanHonHop2N
 * @property string $phanHonHop2P
 * @property string $phanHonHop2K
 * @property string $laiVay
 * @property string $khauHao
 * @property string $nhienLieu
 * @property string $chiPhiKhac
 * @property string $giaBinhQuan
 * @property string $sanLuongTan
 * @property string $thuNhapTrongSen
 * @property string $type
 * @property integer $farmerId
 * @property integer $created_at
 * @property integer $updated_at
 */
class FormAnalyst extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_analyst';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farmerId', 'created_at', 'updated_at'], 'integer'],
            [['tenChuVuon', 'cmnd', 'dienTich', 'congLamCoCong', 'congLamCoDong', 'congTaoHinhCong', 'congTaoHinhDong', 'congBonPhanCong', 'congBonPhanDong', 'congThuHaiCong', 'congThuHaiDong', 'congSoCheCong', 'congSoCheDong', 'congTuoiCong', 'congTuoiDong', 'congPhunThuocCong', 'congPhunThuocDong', 'congKhacCong', 'congKhacDong', 'thuocSauCong', 'thuocsauDong', 'thuocBenhCong', 'thuocBenhDong', 'phanBonLaCong', 'phanBonLaDong', 'phanHuuCoCong', 'phanHuuCoDong', 'voiNongNghiepCong', 'voiNongNghiepDong', 'phanViSinhCong', 'phanViSinhDong', 'phanDamSaCong', 'phanDamSaDong', 'phanDamUreCong', 'phanDamUreDong', 'phanLanCong', 'phanLanDong', 'phanKaliCong', 'phanKaliDong', 'phanHonHop1Cong', 'phanHonHop1Dong', 'phanHonHop1N', 'phanHonHop1P', 'phanHonHop1K', 'phanHonHop2Cong', 'phanHonHop2Dong', 'phanHonHop2N', 'phanHonHop2P', 'phanHonHop2K', 'laiVay', 'khauHao', 'nhienLieu', 'chiPhiKhac', 'giaBinhQuan', 'sanLuongTan', 'thuNhapTrongSen', 'type'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tenChuVuon' => 'Ten Chu Vuon',
            'cmnd' => 'Cmnd',
            'dienTich' => 'Dien Tich',
            'congLamCoCong' => 'Cong Lam Co Cong',
            'congLamCoDong' => 'Cong Lam Co Dong',
            'congTaoHinhCong' => 'Cong Tao Hinh Cong',
            'congTaoHinhDong' => 'Cong Tao Hinh Dong',
            'congBonPhanCong' => 'Cong Bon Phan Cong',
            'congBonPhanDong' => 'Cong Bon Phan Dong',
            'congThuHaiCong' => 'Cong Thu Hai Cong',
            'congThuHaiDong' => 'Cong Thu Hai Dong',
            'congSoCheCong' => 'Cong So Che Cong',
            'congSoCheDong' => 'Cong So Che Dong',
            'congTuoiCong' => 'Cong Tuoi Cong',
            'congTuoiDong' => 'Cong Tuoi Dong',
            'congPhunThuocCong' => 'Cong Phun Thuoc Cong',
            'congPhunThuocDong' => 'Cong Phun Thuoc Dong',
            'congKhacCong' => 'Cong Khac Cong',
            'congKhacDong' => 'Cong Khac Dong',
            'thuocSauCong' => 'Thuoc Sau Cong',
            'thuocsauDong' => 'Thuocsau Dong',
            'thuocBenhCong' => 'Thuoc Benh Cong',
            'thuocBenhDong' => 'Thuoc Benh Dong',
            'phanBonLaCong' => 'Phan Bon La Cong',
            'phanBonLaDong' => 'Phan Bon La Dong',
            'phanHuuCoCong' => 'Phan Huu Co Cong',
            'phanHuuCoDong' => 'Phan Huu Co Dong',
            'voiNongNghiepCong' => 'Voi Nong Nghiep Cong',
            'voiNongNghiepDong' => 'Voi Nong Nghiep Dong',
            'phanViSinhCong' => 'Phan Vi Sinh Cong',
            'phanViSinhDong' => 'Phan Vi Sinh Dong',
            'phanDamSaCong' => 'Phan Dam Sa Cong',
            'phanDamSaDong' => 'Phan Dam Sa Dong',
            'phanDamUreCong' => 'Phan Dam Ure Cong',
            'phanDamUreDong' => 'Phan Dam Ure Dong',
            'phanLanCong' => 'Phan Lan Cong',
            'phanLanDong' => 'Phan Lan Dong',
            'phanKaliCong' => 'Phan Kali Cong',
            'phanKaliDong' => 'Phan Kali Dong',
            'phanHonHop1Cong' => 'Phan Hon Hop1 Cong',
            'phanHonHop1Dong' => 'Phan Hon Hop1 Dong',
            'phanHonHop1N' => 'Phan Hon Hop1 N',
            'phanHonHop1P' => 'Phan Hon Hop1 P',
            'phanHonHop1K' => 'Phan Hon Hop1 K',
            'phanHonHop2Cong' => 'Phan Hon Hop2 Cong',
            'phanHonHop2Dong' => 'Phan Hon Hop2 Dong',
            'phanHonHop2N' => 'Phan Hon Hop2 N',
            'phanHonHop2P' => 'Phan Hon Hop2 P',
            'phanHonHop2K' => 'Phan Hon Hop2 K',
            'laiVay' => 'Lai Vay',
            'khauHao' => 'Khau Hao',
            'nhienLieu' => 'Nhien Lieu',
            'chiPhiKhac' => 'Chi Phi Khac',
            'giaBinhQuan' => 'Gia Binh Quan',
            'sanLuongTan' => 'San Luong Tan',
            'thuNhapTrongSen' => 'Thu Nhap Trong Sen',
            'type' => 'Type',
            'farmerId' => 'Farmer ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

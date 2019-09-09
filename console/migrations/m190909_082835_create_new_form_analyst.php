<?php

use yii\db\Migration;

class m190909_082835_create_new_form_analyst extends Migration
{
    public function up()
    {
        $this->createTable('form_analyst', [
            'id' => $this->primaryKey(11),
            'tenChuVuon' => $this->string(200),
            'cmnd' => $this->string(200),
            'dienTich' => $this->string(200),
            'congLamCoCong' => $this->string(200),
            'congLamCoDong' => $this->string(200),
            'congTaoHinhCong' => $this->string(200),
            'congTaoHinhDong' => $this->string(200),
            'congBonPhanCong' => $this->string(200),
            'congBonPhanDong' => $this->string(200),
            'congThuHaiCong' => $this->string(200),
            'congThuHaiDong' => $this->string(200),
            'congSoCheCong' => $this->string(200),
            'congSoCheDong' => $this->string(200),
            'congTuoiCong' => $this->string(200),
            'congTuoiDong' => $this->string(200),
            'congPhunThuocCong' => $this->string(200),
            'congPhunThuocDong' => $this->string(200),
            'congKhacCong' => $this->string(200),
            'congKhacDong' => $this->string(200),
            'thuocSauCong' => $this->string(200),
            'thuocsauDong' => $this->string(200),
            'thuocBenhCong' => $this->string(200),
            'thuocBenhDong' => $this->string(200),
            'phanBonLaCong' => $this->string(200),
            'phanBonLaDong' => $this->string(200),
            'phanHuuCoCong' => $this->string(200),
            'phanHuuCoDong' => $this->string(200),
            'voiNongNghiepCong' => $this->string(200),
            'voiNongNghiepDong' => $this->string(200),
            'phanViSinhCong' => $this->string(200),
            'phanViSinhDong' => $this->string(200),
            'phanDamSaCong' => $this->string(200),
            'phanDamSaDong' => $this->string(200),
            'phanDamUreCong' => $this->string(200),
            'phanDamUreDong' => $this->string(200),
            'phanLanCong' => $this->string(200),
            'phanLanDong' => $this->string(200),
            'phanKaliCong' => $this->string(200),
            'phanKaliDong' => $this->string(200),
            'phanHonHop1Cong' => $this->string(200),
            'phanHonHop1Dong' => $this->string(200),
            'phanHonHop1N' => $this->string(200),
            'phanHonHop1P' => $this->string(200),
            'phanHonHop1K' => $this->string(200),
            'phanHonHop2Cong' => $this->string(200),
            'phanHonHop2Dong' => $this->string(200),
            'phanHonHop2N' => $this->string(200),
            'phanHonHop2P' => $this->string(200),
            'phanHonHop2K' => $this->string(200),
            'laiVay' => $this->string(200),
            'khauHao' => $this->string(200),
            'nhienLieu' => $this->string(200),
            'chiPhiKhac' => $this->string(200),
            'giaBinhQuan' => $this->string(200),
            'sanLuongTan' => $this->string(200),
            'thuNhapTrongSen' => $this->string(200),
            'type' => $this->string(200),
            'farmerId' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
        $this->createTable('report_form_analyst',[
           'id' => $this->primaryKey(11),
            'sanLuongThucTe' => $this->string(255),
            'nangSuatDatDuoc' => $this->string(255),
            'tongChiPhiThucTeTrongNam' => $this->string(255),
            'nhanCong' => $this->string(255),
            'phanBon' => $this->string(255),
            'tuoi' => $this->string(255),
            'bvtv' => $this->string(255),
            'chiKhac' => $this->string(255),
            'giaThanh' => $this->string(255)
        ]);
    }

    public function down()
    {
        echo "m190909_082835_create_new_form_analyst cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

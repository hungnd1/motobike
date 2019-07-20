<?php

use yii\db\Migration;

class m190719_072828_company_profile extends Migration
{
    public function up()
    {
        $this->createTable('company_profile', [
            'id' => $this->primaryKey(11),
            'ma' => $this->string(255),
            'cmnd' => $this->string(255),
            'ten' => $this->string(255),
            'ho' => $this->string(255),
            'gioi_tinh' => $this->string(255),
            'thon_lang' => $this->string(500),
            'huyen' => $this->string(500),
            'thanh_pho' => $this->string(500),
            'sdt' => $this->string(255),
            'email' => $this->string(255),
            'nam_sinh' => $this->string(255),
            'so_giay_to_chung_nhan' => $this->string(255),
            'vi_do_gps' => $this->string(255),
            'kinh_do_gps' => $this->string(255),
            'ten_nguoi_dung' => $this->string(500),
            'loai_ca_phe' => $this->string(255),
            'tong_san_luong_nam_nay' => $this->string(255),
            'tong_san_luong_nam_ngoai' => $this->string(255),
            'san_luong_ban_giao_nam_ngoai' => $this->string(255),
            'san_luong_ban_giao_2_nam_truoc' => $this->string(255),
            'san_luong_ban_giao_3_nam_truoc' => $this->string(255),
            'nguoi_cmnd' => $this->string(255),
            'nguoi_ten' => $this->string(255),
            'nguoi_ho' => $this->string(255),
            'nguoi_gioi_tinh' => $this->string(255),
            'nguoi_email' => $this->string(255),
            'nguoi_sdt' => $this->string(255),
            'thanh_vien_nhom' => $this->string(255),
            'chung_nhan_tu_nam' => $this->string(255),
            'chuong_trinh_chung_nhan_khac' => $this->string(255),
            'chung_nhan_khac' => $this->string(255),
            'cmnd_thanh_tra' => $this->string(255),
            'hoten_thanh_tra' => $this->string(255),
            'nam_thanh_tra' => $this->string(255),
            'thang_thanh_tra' => $this->string(255),
            'sl_cong_nhan_thoi_vu' => $this->string(255),
            'sl_cong_nhan_dai_han' => $this->string(255),
            'tong_so_vuon_ca_phe' => $this->string(255),
            'tong_so_dien_tich_chung_nhan' => $this->string(255),
            'tong_so_dien_tich_cac_vuon' => $this->string(255),
            'id_number' => $this->string(255),
            'id_company' => $this->integer(11)
        ]);
        $this->createTable('company', [
            'id' => $this->primaryKey(11),
            'username' => $this->string(255),
            'password' => $this->string(500),
            'file'=> $this->string(500),
            'status' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190719_072828_company_profile cannot be reverted.\n";

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

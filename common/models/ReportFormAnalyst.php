<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_form_analyst".
 *
 * @property integer $id
 * @property string $sanLuongThucTe
 * @property string $nangSuatDatDuoc
 * @property string $tongChiPhiThucTeTrongNam
 * @property string $nhanCong
 * @property string $phanBon
 * @property string $tuoi
 * @property string $bvtv
 * @property string $chiKhac
 * @property string $giaThanh
 * @property string $nhanCongPhanTram
 * @property string $phanBonPhanTram
 * @property string $tuoiPhanTram
 * @property string $bvtvPhanTram
 * @property string $chiKhacPhanTram
 * @property string $giaBan
 * @property string $loiNhuan
 * @property string $tongLoiNhuan
 * @property integer $form_id
 */
class ReportFormAnalyst extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_form_analyst';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'integer'],
            [['sanLuongThucTe', 'nangSuatDatDuoc', 'tongChiPhiThucTeTrongNam', 'nhanCong', 'phanBon', 'tuoi', 'bvtv', 'chiKhac', 'giaThanh'], 'string', 'max' => 255],
            [['nhanCongPhanTram', 'phanBonPhanTram', 'tuoiPhanTram','bvtvPhanTram', 'chiKhacPhanTram', 'giaBan', 'loiNhuan', 'tongLoiNhuan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sanLuongThucTe' => 'San Luong Thuc Te',
            'nangSuatDatDuoc' => 'Nang Suat Dat Duoc',
            'tongChiPhiThucTeTrongNam' => 'Tong Chi Phi Thuc Te Trong Nam',
            'nhanCong' => 'Nhan Cong',
            'phanBon' => 'Phan Bon',
            'tuoi' => 'Tuoi',
            'bvtv' => 'Bvtv',
            'chiKhac' => 'Chi Khac',
            'giaThanh' => 'Gia Thanh',
        ];
    }
}

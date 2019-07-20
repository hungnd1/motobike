<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CompanyProfile;

/**
 * CompanyProfileSearch represents the model behind the search form of `common\models\CompanyProfile`.
 */
class CompanyProfileSearch extends CompanyProfile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_company'], 'integer'],
            [['ma', 'cmnd', 'ten', 'ho', 'gioi_tinh', 'thon_lang', 'huyen', 'thanh_pho', 'sdt', 'email', 'nam_sinh', 'so_giay_to_chung_nhan', 'vi_do_gps', 'kinh_do_gps', 'ten_nguoi_dung', 'loai_ca_phe', 'tong_san_luong_nam_nay', 'tong_san_luong_nam_ngoai', 'san_luong_ban_giao_nam_ngoai', 'san_luong_ban_giao_2_nam_truoc', 'san_luong_ban_giao_3_nam_truoc', 'nguoi_cmnd', 'nguoi_ten', 'nguoi_ho', 'nguoi_gioi_tinh', 'nguoi_email', 'nguoi_sdt', 'thanh_vien_nhom', 'chung_nhan_tu_nam', 'chuong_trinh_chung_nhan_khac', 'chung_nhan_khac', 'cmnd_thanh_tra', 'hoten_thanh_tra', 'nam_thanh_tra', 'thang_thanh_tra', 'sl_cong_nhan_thoi_vu', 'sl_cong_nhan_dai_han', 'tong_so_vuon_ca_phe', 'tong_so_dien_tich_chung_nhan', 'tong_so_dien_tich_cac_vuon', 'id_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CompanyProfile::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_company' => $this->id_company,
        ]);

        $query->andFilterWhere(['like', 'ma', $this->ma])
            ->andFilterWhere(['like', 'cmnd', $this->cmnd])
            ->andFilterWhere(['like', 'ten', $this->ten])
            ->andFilterWhere(['like', 'ho', $this->ho])
            ->andFilterWhere(['like', 'gioi_tinh', $this->gioi_tinh])
            ->andFilterWhere(['like', 'thon_lang', $this->thon_lang])
            ->andFilterWhere(['like', 'huyen', $this->huyen])
            ->andFilterWhere(['like', 'thanh_pho', $this->thanh_pho])
            ->andFilterWhere(['like', 'sdt', $this->sdt])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'nam_sinh', $this->nam_sinh])
            ->andFilterWhere(['like', 'so_giay_to_chung_nhan', $this->so_giay_to_chung_nhan])
            ->andFilterWhere(['like', 'vi_do_gps', $this->vi_do_gps])
            ->andFilterWhere(['like', 'kinh_do_gps', $this->kinh_do_gps])
            ->andFilterWhere(['like', 'ten_nguoi_dung', $this->ten_nguoi_dung])
            ->andFilterWhere(['like', 'loai_ca_phe', $this->loai_ca_phe])
            ->andFilterWhere(['like', 'tong_san_luong_nam_nay', $this->tong_san_luong_nam_nay])
            ->andFilterWhere(['like', 'tong_san_luong_nam_ngoai', $this->tong_san_luong_nam_ngoai])
            ->andFilterWhere(['like', 'san_luong_ban_giao_nam_ngoai', $this->san_luong_ban_giao_nam_ngoai])
            ->andFilterWhere(['like', 'san_luong_ban_giao_2_nam_truoc', $this->san_luong_ban_giao_2_nam_truoc])
            ->andFilterWhere(['like', 'san_luong_ban_giao_3_nam_truoc', $this->san_luong_ban_giao_3_nam_truoc])
            ->andFilterWhere(['like', 'nguoi_cmnd', $this->nguoi_cmnd])
            ->andFilterWhere(['like', 'nguoi_ten', $this->nguoi_ten])
            ->andFilterWhere(['like', 'nguoi_ho', $this->nguoi_ho])
            ->andFilterWhere(['like', 'nguoi_gioi_tinh', $this->nguoi_gioi_tinh])
            ->andFilterWhere(['like', 'nguoi_email', $this->nguoi_email])
            ->andFilterWhere(['like', 'nguoi_sdt', $this->nguoi_sdt])
            ->andFilterWhere(['like', 'thanh_vien_nhom', $this->thanh_vien_nhom])
            ->andFilterWhere(['like', 'chung_nhan_tu_nam', $this->chung_nhan_tu_nam])
            ->andFilterWhere(['like', 'chuong_trinh_chung_nhan_khac', $this->chuong_trinh_chung_nhan_khac])
            ->andFilterWhere(['like', 'chung_nhan_khac', $this->chung_nhan_khac])
            ->andFilterWhere(['like', 'cmnd_thanh_tra', $this->cmnd_thanh_tra])
            ->andFilterWhere(['like', 'hoten_thanh_tra', $this->hoten_thanh_tra])
            ->andFilterWhere(['like', 'nam_thanh_tra', $this->nam_thanh_tra])
            ->andFilterWhere(['like', 'thang_thanh_tra', $this->thang_thanh_tra])
            ->andFilterWhere(['like', 'sl_cong_nhan_thoi_vu', $this->sl_cong_nhan_thoi_vu])
            ->andFilterWhere(['like', 'sl_cong_nhan_dai_han', $this->sl_cong_nhan_dai_han])
            ->andFilterWhere(['like', 'tong_so_vuon_ca_phe', $this->tong_so_vuon_ca_phe])
            ->andFilterWhere(['like', 'tong_so_dien_tich_chung_nhan', $this->tong_so_dien_tich_chung_nhan])
            ->andFilterWhere(['like', 'tong_so_dien_tich_cac_vuon', $this->tong_so_dien_tich_cac_vuon])
            ->andFilterWhere(['like', 'id_number', $this->id_number]);

        return $dataProvider;
    }
}

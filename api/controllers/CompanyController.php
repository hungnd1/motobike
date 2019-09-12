<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jun-17
 * Time: 10:25 PM
 */

namespace api\controllers;


use api\helpers\Message;
use api\helpers\UserHelpers;
use api\models\CompanyNews;
use api\models\CompanyQA;
use api\models\FormAnalyst;
use common\models\Company;
use common\models\ReportFormAnalyst;
use common\models\Subscriber;
use Yii;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

class CompanyController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'upload-company' => ['POST'],
            'get-list-farmer' => ['GET'],
            'question-and-answer' => ['POST'],
            'search' => ['GET'],
            'get-list-news' => ['GET'],
            'detail-news' => ['GET'],
            'form-analyst' => ['POST'],
            'get-result' => ['GET']
        ];
    }

    public function actionLogin()
    {
        UserHelpers::manualLogin();
        $username = trim($this->getParameterPost('username', ''));
        $password = trim($this->getParameterPost('password', ''));
        if (!$username) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên tài khoản')]));
        }

        if (!$password) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Mật khẩu')]));
        }

        /** @var  $company Company */
        $company = Company::find()->andWhere(['username' => $username])->andWhere(['password' => $password])->one();
        if (!$company) {
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        return ['message' => Message::getLoginSuccessMessage(),
            'id' => $company->id
        ];
    }

    public function actionUploadCompany()
    {
        UserHelpers::manualLogin();

        $id = $this->getParameterPost('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id công ty')]));
        }
        $fileBase64 = $this->getParameterPost('file', '');
        $congTy = $this->getParameterPost('ten', '');
        $diachi = $this->getParameterPost('dia_chi', '');
        $kinhdoanh = $this->getParameterPost('kinh_doanh', '');
        $sanpham = $this->getParameterPost('san_pham', '');
        $dientich = $this->getParameterPost('dien_tich', '');
        $website = $this->getParameterPost('website', '');
        $extension = $this->getParameterPost('extension', '');
        /** @var  $company Company */
        $company = Company::find()->andWhere(['id' => $id])->one();
        if (!$company) {
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        $arr = array(
            'ten' => $congTy,
            'diachi' => $diachi,
            'kinhdoanh' => $kinhdoanh,
            'sanpham' => $sanpham,
            'dientich' => $dientich,
            'website' => $website
        );
        $company->description = json_encode($arr);
        if ($fileBase64) {
            $binary = base64_decode($fileBase64, true);
            $url = Yii::getAlias('@company') . DIRECTORY_SEPARATOR;
            $file_name = Yii::$app->user->id . '.' . uniqid() . time() . $extension;
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
            file_put_contents($url . $file_name, $binary);
            $file = fopen($url . $file_name, 'wb');
            fwrite($file, $binary);
            fclose($file);
            $company->file = $file_name;
        }
        if (!$company->save()) {
            throw new ServerErrorHttpException("Lưu thông tin công ty thất bại");
        }
        return ['message' => "Lưu thông tin thành công",
            'id' => $company->id
        ];
    }

    public function actionGetCompany($id)
    {
        UserHelpers::manualLogin();
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id công ty')]));
        }
        $company = \api\models\Company::find()->andWhere(['id' => $id])->one();
        if (!$company) {
            throw new ServerErrorHttpException("Tài khoản hoặc mật khẩu của bạn không chính xác");
        }
        return $company;
    }

    public function actionGetListFarmer($id)
    {
        UserHelpers::manualLogin();
        $query = \api\models\CompanyProfile::find()
            ->andWhere(['id_company' => $id])
            ->andWhere('kinh_do_gps is not null')
            ->andWhere('kinh_do_gps is not null');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['ma' => SORT_ASC],
            ],
        ]);
        return $dataProvider;

    }

    public function actionSearch($keyword = '', $id)
    {
        UserHelpers::manualLogin();
        $query = \api\models\CompanyProfile::find()
            ->andWhere(['id_company' => $id])
            ->andWhere('vi_do_gps is not null')
            ->andWhere('kinh_do_gps is not null')
            ->andFilterWhere(['or',
                ['like', 'cmnd', $keyword],
                ['like', 'ten', $keyword]
            ]);
        $defaultSort = ['ten' => SORT_ASC];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 1000],
            ],
            'sort' => [
                'defaultOrder' => $defaultSort,
            ],
        ]);
        return $dataProvider;
    }

    public function actionGetListQuestionAnswer($id)
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id công ty')]));
        }

        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
        $query = CompanyQa::find()->andWhere(['company_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);
        return $dataProvider;

    }

    public function actionQuestionSearch($keyword = '', $id)
    {
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id công ty')]));
        }

        $query = CompanyQa::find()->andWhere(['like', 'lower(question)', strtolower($keyword)])
            ->orWhere(['like', 'lower(answer)', strtolower($keyword)]);
        $defaultSort = ['created_at' => SORT_DESC];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => $defaultSort,
            ],
        ]);
        return $dataProvider;
    }

    public function actionDetailQuestion()
    {
        $id = $this->getParameter('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id')]));
        }
        $question = CompanyQa::findOne([$id]);
        if ($question) {
            return $question;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionQuestionAndAnswer()
    {

        UserHelpers::manualLogin();
        $question = $this->getParameterPost('answer', null);
        $id = $this->getParameterPost('id', null);
        $base = $this->getParameterPost('image', '');
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Id')]));
        }
        if (!$question) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Câu trả lời')]));
        }
        $file_name = '';
        if ($base) {
            $binary = base64_decode($base, true);
            $url = Yii::getAlias('@question') . DIRECTORY_SEPARATOR;
            $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.jpg';
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
            file_put_contents($url . $file_name, $binary);
            $file = fopen($url . $file_name, 'wb');
            fwrite($file, $binary);
            fclose($file);
        }
        /** @var  $companyQA \common\models\CompanyQa */
        $companyQA = \common\models\CompanyQa::find()->andWhere(['id' => $id])->one();
        if (!$companyQA) {
            throw new ServerErrorHttpException(Yii::t('app', 'Không tồn tại câu hỏi'));
        }
        $companyQA->answer = $question;
        $companyQA->image = $file_name;
        $companyQA->updated_at = time();
        $companyQA->status = Company::STATUS_ACTIVE;

        if ($companyQA->save(false)) {
//            shell_exec("/usr/bin/nohup  ./auto_answer.sh $question_answer->id > /dev/null 2>&1 &");
            return [
                'message' => Yii::t('app', 'Bạn đã trả lời câu hỏi thành công'),
            ];
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGetListNews()
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;
        $id = $this->getParameter('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'ID công ty')]));
        }
        $page = isset($_GET['page']) && $_GET['page'] > 1 ? $_GET['page'] - 1 : 0;
//        $query = News::find()->andWhere(['status' => News::STATUS_ACTIVE])->orderBy(['updated_at' => SORT_DESC]);
        if ($id) {
            $query = CompanyNews::find()
                ->andWhere(['status' => CompanyNews::STATUS_ACTIVE])
                ->andWhere(['company_id' => (int)$id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        if ($query->one()) {
            return $dataProvider;
        } else {
            throw new ServerErrorHttpException("Danh mục này đang được cập nhật nội dung!");
        }

    }

    public function actionDetailNews()
    {
        $id = $this->getParameter('id', '');
        if (!$id) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'id')]));
        }
        $com = \common\models\CompanyNews::findOne([$id]);
        if ($com) {
            return $com;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionSubmitForm()
    {
        UserHelpers::manualLogin();
        /** @var  $subscriber Subscriber */
        $subscriber = Yii::$app->user->identity;

        $formAnalyst = $this->getParameterPost('formAnalyst', '');
        $formAnalyst = json_decode($formAnalyst, true);
        $type = isset($formAnalyst['type']) && $formAnalyst['type'] ? $formAnalyst['type'] : 3;
        $farmerId = isset($formAnalyst['farmerId']) && $formAnalyst['farmerId']? $formAnalyst['farmerId'] : $subscriber->farmer_id;
        $month = isset($formAnalyst['month']) ? $formAnalyst['month'] : 0;

        if ($type == 3) {
            $form = \common\models\FormAnalyst::find()
                ->andWhere(['type' => $type])
                ->andWhere(['farmerId' => $farmerId])
                ->andWhere(['month' => $month])->one();
            if (!$form) {
                $form = new \common\models\FormAnalyst();
                $form->farmerId = $farmerId;
                $form->month = $month;
                $form->type = $type;
            }
        } else if ($type == 1) {
            $form = new \common\models\FormAnalyst();
            $form->farmerId = $farmerId;
            $form->month = $month;
            $form->type = $type;
        }
        $form = new \common\models\FormAnalyst();
        $form->tenChuVuon = isset($formAnalyst['tenChuVuon']) ? $formAnalyst['tenChuVuon'] : "";

        if (!$form->tenChuVuon) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Tên chủ vuòn')]));
        }
        $form->cmnd = isset($formAnalyst['cmnd']) ? $formAnalyst['cmnd'] : "";
        if (!$form->cmnd) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'CMND')]));
        }
        $form->dienTich = isset($formAnalyst['dienTich']) ? $formAnalyst['dienTich'] : 0;
        if (!$form->dienTich) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Diện tích')]));
        }
        $form->congLamCoCong = isset($formAnalyst['congLamCoCong']) ? $formAnalyst['congLamCoCong'] : 0;
        $form->congLamCoDong = isset($formAnalyst['congLamCoDong']) ? $formAnalyst['congLamCoDong'] : 0;
        if (!$form->congLamCoCong || !$form->congLamCoDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công làm cỏ')]));
        }

        $form->congTaoHinhCong = isset($formAnalyst['congTaoHinhCong']) ? $formAnalyst['congTaoHinhCong'] : 0;
        $form->congTaoHinhDong = isset($formAnalyst['congTaoHinhDong']) ? $formAnalyst['congTaoHinhDong'] : 0;

        if (!$form->congTaoHinhCong || !$form->congTaoHinhDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công tạo hình')]));
        }

        $form->congBonPhanCong = isset($formAnalyst['congBonPhanCong']) ? $formAnalyst['congBonPhanCong'] : 0;
        $form->congBonPhanDong = isset($formAnalyst['congBonPhanDong']) ? $formAnalyst['congBonPhanDong'] : 0;

        if (!$form->congBonPhanCong || !$form->congBonPhanDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công bón phân')]));
        }

        $form->congThuHaiCong = isset($formAnalyst['congThuHaiCong']) ? $formAnalyst['congThuHaiCong'] : 0;
        $form->congThuHaiDong = isset($formAnalyst['congThuHaiDong']) ? $formAnalyst['congThuHaiDong'] : 0;

        if (!$form->congThuHaiCong || !$form->congThuHaiDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công thu hái')]));
        }

        $form->congSoCheCong = isset($formAnalyst['congSoCheCong']) ? $formAnalyst['congSoCheCong'] : 0;
        $form->congSoCheDong = isset($formAnalyst['congSoCheDong']) ? $formAnalyst['congSoCheDong'] : 0;

        if (!$form->congSoCheCong || !$form->congSoCheDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công sơ chế')]));
        }

        $form->congTuoiCong = isset($formAnalyst['congTuoiCong']) ? $formAnalyst['congTuoiCong'] : 0;
        $form->congTuoiDong = isset($formAnalyst['congTuoiDong']) ? $formAnalyst['congTuoiDong'] : 0;

        if (!$form->congTuoiCong || !$form->congTuoiDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công tưới')]));
        }

        $form->congPhunThuocCong = isset($formAnalyst['congPhunThuocCong']) ? $formAnalyst['congPhunThuocCong'] : 0;
        $form->congPhunThuocDong = isset($formAnalyst['congPhunThuocDong']) ? $formAnalyst['congPhunThuocDong'] : 0;

        if (!$form->congPhunThuocCong || !$form->congPhunThuocDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công phun thuốc')]));
        }

        $form->congKhacCong = isset($formAnalyst['congKhacCong']) ? $formAnalyst['congKhacCong'] : 0;
        $form->congKhacDong = isset($formAnalyst['congKhacDong']) ? $formAnalyst['congKhacDong'] : 0;

        if (!$form->congKhacCong || !$form->congKhacDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công khác')]));
        }

        $form->thuocSauCong = isset($formAnalyst['thuocSauCong']) ? $formAnalyst['thuocSauCong'] : 0;
        $form->thuocsauDong = isset($formAnalyst['thuocsauDong']) ? $formAnalyst['thuocsauDong'] : 0;

        if (!$form->thuocSauCong || !$form->thuocsauDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Thuốc sâu')]));
        }

        $form->thuocBenhCong = isset($formAnalyst['thuocBenhCong']) ? $formAnalyst['thuocBenhCong'] : 0;
        $form->thuocBenhDong = isset($formAnalyst['thuocBenhDong']) ? $formAnalyst['thuocBenhDong'] : 0;

        if (!$form->thuocBenhCong || !$form->thuocBenhDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Thuốc bệnh')]));
        }

        $form->phanBonLaCong = isset($formAnalyst['phanBonLaCong']) ? $formAnalyst['phanBonLaCong'] : 0;
        $form->phanBonLaDong = isset($formAnalyst['phanBonLaDong']) ? $formAnalyst['phanBonLaDong'] : 0;

        if (!$form->phanBonLaCong || !$form->phanBonLaDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân bón lá')]));
        }

        $form->phanHuuCoCong = isset($formAnalyst['phanHuuCoCong']) ? $formAnalyst['phanHuuCoCong'] : 0;
        $form->phanHuuCoDong = isset($formAnalyst['phanHuuCoDong']) ? $formAnalyst['phanHuuCoDong'] : 0;

        if (!$form->phanHuuCoCong || !$form->phanHuuCoDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân hữu cơ')]));
        }

        $form->voiNongNghiepCong = isset($formAnalyst['voiNongNghiepCong']) ? $formAnalyst['voiNongNghiepCong'] : 0;
        $form->voiNongNghiepDong = isset($formAnalyst['voiNongNghiepDong']) ? $formAnalyst['voiNongNghiepDong'] : 0;

        if (!$form->voiNongNghiepCong || !$form->voiNongNghiepDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Vôi nông nghiệp')]));
        }

        $form->phanViSinhCong = isset($formAnalyst['phanViSinhCong']) ? $formAnalyst['phanViSinhCong'] : 0;
        $form->phanViSinhDong = isset($formAnalyst['phanViSinhDong']) ? $formAnalyst['phanViSinhDong'] : 0;

        if (!$form->phanViSinhCong || !$form->phanViSinhDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân vi sinh')]));
        }

        $form->phanDamSaCong = isset($formAnalyst['phanDamSaCong']) ? $formAnalyst['phanDamSaCong'] : 0;
        $form->phanDamSaDong = isset($formAnalyst['phanDamSaDong']) ? $formAnalyst['phanDamSaDong'] : 0;

        if (!$form->phanDamSaCong || !$form->phanDamSaDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân đạm SA')]));
        }

        $form->phanDamUreCong = isset($formAnalyst['phanDamUreCong']) ? $formAnalyst['phanDamUreCong'] : 0;
        $form->phanDamUreDong = isset($formAnalyst['phanDamUreDong']) ? $formAnalyst['phanDamUreDong'] : 0;

        if (!$form->phanDamUreCong || !$form->phanDamUreDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân đạm Ure')]));
        }

        $form->phanLanCong = isset($formAnalyst['phanLanCong']) ? $formAnalyst['phanLanCong'] : 0;
        $form->phanLanDong = isset($formAnalyst['phanLanDong']) ? $formAnalyst['phanLanDong'] : 0;

        if (!$form->phanLanCong || !$form->phanLanDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân lân')]));
        }

        $form->phanKaliCong = isset($formAnalyst['phanKaliCong']) ? $formAnalyst['phanKaliCong'] : 0;
        $form->phanKaliDong = isset($formAnalyst['phanKaliDong']) ? $formAnalyst['phanKaliDong'] : 0;

        if (!$form->phanKaliCong || !$form->phanKaliDong) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân kali')]));
        }

        $form->phanHonHop1Cong = isset($formAnalyst['phanHonHop1Cong']) ? $formAnalyst['phanHonHop1Cong'] : 0;
        $form->phanHonHop1Dong = isset($formAnalyst['phanHonHop1Dong']) ? $formAnalyst['phanHonHop1Dong'] : 0;
        $form->phanHonHop1N = isset($formAnalyst['phanHonHop1N']) ? $formAnalyst['phanHonHop1N'] : 0;
        $form->phanHonHop1P = isset($formAnalyst['phanHonHop1P']) ? $formAnalyst['phanHonHop1P'] : 0;
        $form->phanHonHop1K = isset($formAnalyst['phanHonHop1K']) ? $formAnalyst['phanHonHop1K'] : 0;

        if (!$form->phanHonHop1Cong || !$form->phanHonHop1Dong || !$form->phanHonHop1N || !$form->phanHonHop1P || !$form->phanHonHop1K) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân hỗn hợp 1')]));
        }

        $form->phanHonHop2Cong = isset($formAnalyst['phanHonHop2Cong']) ? $formAnalyst['phanHonHop2Cong'] : 0;
        $form->phanHonHop2Dong = isset($formAnalyst['phanHonHop2Dong']) ? $formAnalyst['phanHonHop2Dong'] : 0;
        $form->phanHonHop2N = isset($formAnalyst['phanHonHop2N']) ? $formAnalyst['phanHonHop2N'] : 0;
        $form->phanHonHop2P = isset($formAnalyst['phanHonHop2P']) ? $formAnalyst['phanHonHop2P'] : 0;
        $form->phanHonHop2K = isset($formAnalyst['phanHonHop2K']) ? $formAnalyst['phanHonHop2K'] : 0;

        if (!$form->phanHonHop2Cong || !$form->phanHonHop2Dong || !$form->phanHonHop2N || !$form->phanHonHop2P || !$form->phanHonHop2K) {
            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Phân hỗn hợp 2')]));
        }

        $form->laiVay = isset($formAnalyst['laiVay']) ? $formAnalyst['laiVay'] : 0;

//        if (!$form->laiVay) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Lãi vay')]));
//        }

        $form->khauHao = isset($formAnalyst['khauHao']) ? $formAnalyst['khauHao'] : 0;

//        if (!$form->khauHao) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Khấu hao')]));
//        }

        $form->nhienLieu = isset($formAnalyst['nhienLieu']) ? $formAnalyst['nhienLieu'] : 0;

//        if (!$form->nhienLieu) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Nhiên liệu')]));
//        }

        $form->chiPhiKhac = isset($formAnalyst['chiPhiKhac']) ? $formAnalyst['chiPhiKhac'] : 0;

//        if (!$form->chiPhiKhac) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Chi phí khác')]));
//        }

        $form->giaBinhQuan = isset($formAnalyst['giaBinhQuan']) ? $formAnalyst['giaBinhQuan'] : 0;

//        if (!$form->giaBinhQuan) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Giá bình quân')]));
//        }

        $form->sanLuongTan = isset($formAnalyst['sanLuongTan']) ? $formAnalyst['sanLuongTan'] : 0;

//        if (!$form->congLamCoCong) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công làm cỏ')]));
//        }

        $form->thuNhapTrongSen = isset($formAnalyst['thuNhapTrongSen']) ? $formAnalyst['thuNhapTrongSen'] : 0;

//        if (!$form->congLamCoCong) {
//            throw new InvalidValueException($this->replaceParam(Message::getNullValueMessage(), [Yii::t('app', 'Công làm cỏ')]));
//        }


        $form->created_at = time();
        $form->updated_at = time();
        if (!$form->save(false)) {
            throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
        };
        $reportFormAnalyst = new ReportFormAnalyst();
        $reportFormAnalyst->sanLuongThucTe = "1 T";
        $reportFormAnalyst->nangSuatDatDuoc = "1 T/ha";
        $reportFormAnalyst->tongChiPhiThucTeTrongNam = "100 tr.đ";
        $reportFormAnalyst->nhanCong = "20";
        $reportFormAnalyst->nhanCongPhanTram = "20%";
        $reportFormAnalyst->phanBon = "10";
        $reportFormAnalyst->phanBonPhanTram = "20%";
        $reportFormAnalyst->tuoi = "10";
        $reportFormAnalyst->tuoiPhanTram = "10%";
        $reportFormAnalyst->bvtv = "20";
        $reportFormAnalyst->bvtvPhanTram = "20%";
        $reportFormAnalyst->chiKhac = "20";
        $reportFormAnalyst->chiKhacPhanTram = "20%";
        $reportFormAnalyst->form_id = $form->id;
        $reportFormAnalyst->giaThanh = "20 tr.đ";
        $reportFormAnalyst->giaBan = '20 VNĐ';
        $reportFormAnalyst->loiNhuan = '20 VNĐ';
        $reportFormAnalyst->tongLoiNhuan = '20 VNĐ';
        if ($reportFormAnalyst->save(false)) {
            return $reportFormAnalyst;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }

    public function actionGetResult($farmer_id)
    {
        UserHelpers::manualLogin();
        $reportForm = ReportFormAnalyst::find()
            ->innerJoin('form_analyst', 'report_form_analyst.form_id = form_analyst.id')
            ->andWhere(['form_analyst.farmerId' => $farmer_id])
            ->andWhere(['type' => 3])
            ->orderBy(['form_analyst.id' => SORT_DESC])->one();
        return $reportForm;
    }

    public function actionGetGraphic($month = 0, $farmer_id)
    {
        UserHelpers::manualLogin();
        if($month){
            $formAnalyst = \common\models\FormAnalyst::find()
                ->andWhere(['farmerId' => $farmer_id])
                ->andWhere(['month' => $month])
                ->andWhere(['type' => 3])
                ->orderBy(['id' => SORT_DESC])->one();
        }else{
            $formAnalyst = \common\models\FormAnalyst::find()
                ->andWhere(['farmerId' => $farmer_id])
//                ->andWhere(['month' => $month])
                ->andWhere(['type' => 3])
                ->orderBy(['id' => SORT_DESC])->one();
        }
        return $formAnalyst;
    }
}

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
use common\models\Company;
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
            'detail-news' => ['GET']
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
            ->andWhere(['id_number'=>$keyword]);
        $defaultSort = ['id_number' => SORT_ASC];

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
        $query = CompanyQa::find()->andWhere(['company_id'=>$id]);
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
        $companyQA = \common\models\CompanyQa::find()->andWhere(['id'=>$id])->one();
        if(!$companyQA){
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
                'defaultOrder' => ['order' => SORT_DESC],
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
        $com = CompanyNews::findOne([$id]);
        if ($com) {
            return $com;
        }
        throw new ServerErrorHttpException(Yii::t('app', 'Lỗi hệ thống, vui lòng thử lại sau'));
    }
}

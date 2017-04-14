<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\helpers\CUtils;
use common\models\Shopbike;
use common\models\ShopbikeSearch;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ShopbikeController implements the CRUD actions for Shopbike model.
 */
class ShopbikeController extends BaseBEController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update-status-content' => ['post'],
                ],
            ],
            [
                'class' => ActionLogTracking::className(),
                'user' => Yii::$app->user,
            ],
        ]);
    }

    /**
     * Lists all Shopbike models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                $cat = Shopbike::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($cat) {
                    $cat->load($post['Shopbike'][$index], '');
                    if ($cat->update()) {
                        // tao log
                        $ip_address = CUtils::clientIP();

                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    } else {
                        // tao log
                        $ip_address = CUtils::clientIP();

                        echo \yii\helpers\Json::encode(['output' => '', 'message' => Yii::t('app', 'Dữ liệu không hợp lệ')]);
                    }
                } else {
                    echo \yii\helpers\Json::encode(['output' => '', 'message' => Yii::t('app', 'Danh mục không tồn tại')]);
                }
            } // else if nothing to do always return an empty JSON encoded output
            else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }

            return;
        }
        $searchModel = new ShopbikeSearch();
        $params = Yii::$app->request->queryParams;
        Yii::trace($params);
        $params['ShopbikeSearch']['created_at'] = isset($params['ShopbikeSearch']['created_at']) && $params['ShopbikeSearch']['created_at'] !== '' ? strtotime($params['ShopbikeSearch']['created_at']) : '';
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shopbike model.
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
     * Creates a new Shopbike model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shopbike();
        $model->loadDefaultValues();
        $model->setScenario('adminModify');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->password_hash = $model->password;
            $model->setPassword($model->password);
            $model->created_at = time();
            $model->updated_at = time();
            if ($model->status == Shopbike::STATUS_ACTIVE) {
                $model->approved_at = time();
            }
            $file = UploadedFile::getInstance($model, 'avatar');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@content_images') . "/" . $file_name)) {
                    $model->avatar = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            }
            if ($model->save()) {
                // tao log
                \Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Lưu hãng xe thành công'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::info($model->getErrors());
                \Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Lưu hãng xe thất bại'));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shopbike model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->loadDefaultValues();
        $image_old = $model->avatar;
        $model->setScenario('adminModify');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            $file = UploadedFile::getInstance($model, 'avatar');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@content_images') . "/" . $file_name)) {
                    $model->avatar = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            } else {
                $model->avatar = $image_old;
            }
            if ($model->save()) {
                // tao log
                \Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Lưu hãng xe thành công'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::info($model->getErrors());
                \Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Lưu hãng xe thất bại'));
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Shopbike model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Shopbike::STATUS_DELETE;
        $model->updated_at = time();
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Shopbike model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shopbike the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shopbike::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateStatus($id)
    {
        $model = $this->findModel($id);
        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                $content = Shopbike::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($content || $model->id != $content->id) {
                    $content->load($post['Shopbike'][$index], '');
                    $model->updated_at = time();
                    if ($content->update()) {
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    } else {
                        // tao log
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => \Yii::t('app', 'Dữ liệu không hợp lệ')]);
                    }
                } else {
                    echo \yii\helpers\Json::encode(['output' => '', 'message' => \Yii::t('app', 'Dữ liệu không tồn tại')]);
                }
            } // else if nothing to do always return an empty JSON encoded output
            else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }

            return;
        }
    }

    public function actionUpdateStatusContent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $cp = Yii::$app->user->id;

        if (isset($post['ids']) && isset($post['newStatus'])) {
            $ids = $post['ids'];
            $newStatus = $post['newStatus'];
            $contents = Shopbike::findAll($ids);
            $count = 0;

            foreach ($contents as $content) {
                if ($content->spUpdateStatus($newStatus)) {
                    ++$count;
                }
            }

            $successMess = $newStatus == Shopbike::STATUS_DELETE ? Yii::t('app', 'Xóa') : Yii::t('app', 'Cập nhật');

            return [
                'success' => true,
                'message' => $successMess . ' ' . $count . Yii::t('app', ' hãng xe thành công!'),
            ];
        } else {
            return [
                'success' => false,
                'message' => Yii::t('app', 'Không thành công. Vui lòng thử'),
            ];
        }
    }


}

<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\helpers\CUtils;
use common\helpers\CVietnameseTools;
use common\models\Product;
use common\models\ProductSearch;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BaseBEController
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                $cat = Product::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($cat) {
                    $cat->load($post['Product'][$index], '');
                    if ($cat->update()) {
                        // tao log
                        $description = 'UPDATE STATUS CONTENT';
                        $ip_address = CUtils::clientIP();

                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    } else {
                        // tao log
                        $description = 'UPDATE STATUS CONTENT';
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
        $searchModel = new ProductSearch();
        $params = Yii::$app->request->queryParams;
        Yii::trace($params);
        $params['ProductSearch']['created_at'] = isset($params['ProductSearch']['created_at']) && $params['ProductSearch']['created_at'] !== '' ? strtotime($params['ProductSearch']['created_at']) : '';
        $selectedCats = isset($params['ProductSearch']['categoryIds']) ? explode(',', $params['ProductSearch']['categoryIds']) : [];
        $dataProvider = $searchModel->search($params);
        $searchModel->keyword = isset($params['ProductSearch']['keyword']) ? $params['ProductSearch']['keyword'] : '';
        /* @var  $userAccessed User */
        // var_dump($dataProvider);die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectedCats' => $selectedCats,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $active = 1)
    {
        ini_set('memory_limit', '-1');
        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                $index = $post['editableIndex'];
            } // else if nothing to do always return an empty JSON encoded output
            else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }

            return;
        }
        $model = $this->findModel($id);
        //Images
        $imageModel = new \backend\models\Image();
        $images = $model->getImages();
        $imageProvider = new ArrayDataProvider([
            'key' => 'name',
            'allModels' => $images,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        return $this->render('view', [
            'model' => $model,
            'id' => $id,
            'imageModel' => $imageModel,
            'imageProvider' => $imageProvider,
            'active' => $active,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->loadDefaultValues();
        $model->code = rand(10000, 99999);
        $model->setScenario('adminModify');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if (isset(Yii::$app->request->post()['Product']['list_cat_id'])) {
                $model->list_cat_id = Yii::$app->request->post()['Product']['list_cat_id'];
            }
            $model->ascii_name = CVietnameseTools::makeSearchableStr($model->display_name);
            $model->created_at = time();
            $model->updated_at = time();
            if ($model->status == Product::STATUS_ACTIVE) {
                $model->approved_at = time();
            }
            if ($model->save(false)) {
                $model->createCategoryAsm();
                $model->createShopbikeProductAsm();

                $image_slide = Product::convertJsonToArray($model->images);
                // tao log
                \Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Lưu sản phẩm thành công'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::info($model->getErrors());
                \Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Lưu sản phẩm thất bại'));
            }
        }
        $selectedCats = explode(',', $model->list_cat_id);
        // get screenshoot
        $thumbnailInit = [];
        $screenshootInit = [];
        $thumbnailPreview = [];
        $screenshootPreview = [];
        $thumb = [];
        $screenshoot = [];
        $images = Product::convertJsonToArray($model->images);
        foreach ($images as $key => $row) {
            $key = $key + 1;
            $urlDelete = Yii::$app->urlManager->createAbsoluteUrl(['/product/delete-image', 'name' => $row['name'], 'type' => $row['type'], 'product_id' => $model->id]);
            $name = $row['name'];
            $type = $row['type'];
            $value = ['caption' => $name, 'width' => '120px', 'url' => $urlDelete, 'key' => $key];
            $host_file = ((strpos($row['name'], 'http') !== false) || (strpos($row['name'], 'https') !== false)) ? $row['name'] : Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR . $row['name'];
            $preview = Html::img($host_file, ['class' => 'file-preview-image']);
            switch ($row['type']) {

                case Product::IMAGE_TYPE_SCREENSHOOT:
                    $screenshootPreview[] = $preview;
                    $screenshootInit[] = $value;
                    $screenshoot[] = $name;
                    break;
                case Product::IMAGE_TYPE_THUMBNAIL:
                    $thumbnailPreview[] = $preview;
                    $thumbnailInit[] = $value;
                    $thumb[] = $name;
                    break;

            }
            //end screenshoot
        }
        $model->thumbnail = $thumb;
        $model->screenshoot = $screenshoot;
        return $this->render('create', [
            'model' => $model,
            'thumbnailInit' => $thumbnailInit,
            'thumbnailPreview' => $thumbnailPreview,
            'screenshootInit' => $screenshootInit,
            'screenshootPreview' => $screenshootPreview,
            'selectedCats' => $selectedCats,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('adminModify');

        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->ascii_name = CVietnameseTools::makeSearchableStr($model->display_name);
            if (isset(Yii::$app->request->post()['Product']['list_cat_id'])) {
                $model->list_cat_id = Yii::$app->request->post()['Product']['list_cat_id'];
            }

            $model->updated_at = time();
            if ($model->save()) {

                $model->createCategoryAsm();
                $image_slide = Product::convertJsonToArray($model->images);
                // tao log

                \Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Cập nhật Content thành công'));

                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                \Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Cập nhật Content thất bại'));

            }
        }
        // get screenshoot
        $images = Product::convertJsonToArray($model->images);

        $thumbnailInit = [];
        $screenshootInit = [];
        $thumbnailPreview = [];
        $screenshootPreview = [];

        $thumb = [];
        $screenshoot = [];

        foreach ($images as $key => $row) {
            $key = $key + 1;
            $urlDelete = Yii::$app->urlManager->createAbsoluteUrl(['/product/delete-image', 'name' => $row['name'], 'type' => $row['type'], 'content_id' => $model->id]);
            $name = $row['name'];
            $type = $row['type'];
            $value = ['caption' => $name, 'width' => '120px', 'url' => $urlDelete, 'key' => $key];
            $host_file = ((strpos($row['name'], 'http') !== false) || (strpos($row['name'], 'https') !== false)) ? $row['name'] : Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR . $row['name'];
            $preview = Html::img($host_file, ['class' => 'file-preview-image']);
            switch ($row['type']) {

                case Product::IMAGE_TYPE_SCREENSHOOT:
                    $screenshootPreview[] = $preview;
                    $screenshootInit[] = $value;
                    $screenshoot[] = $name;
                    break;
                case Product::IMAGE_TYPE_THUMBNAIL:
                    $thumbnailPreview[] = $preview;
                    $thumbnailInit[] = $value;
                    $thumb[] = $name;
                    break;
            }

            //end screenshoot
        }
        $model->thumbnail = $thumb;
        $model->screenshoot = $screenshoot;

        $selectedCats = $model->getListCatIds();
        $model->list_cat_id = implode(',', $selectedCats);

        Yii::trace($selectedCats);
//        var_dump($screenshootInit);
        //        var_dump($screenshootPreview);exit;
        return $this->render('update', [
            'model' => $model,
            'thumbnailInit' => $thumbnailInit,
            'thumbnailPreview' => $thumbnailPreview,
            'screenshootInit' => $screenshootInit,
            'screenshootPreview' => $screenshootPreview,
            'selectedCats' => $selectedCats,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Product::STATUS_DELETE;
        $model->updated_at = time();
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteImage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $content_id = Yii::$app->request->get('content_id');
        $name = Yii::$app->request->get('name');

        if (!$content_id || !$name) {
            return [
                'success' => false,
                'message' => 'Thiếu tham số!',
                'error' => 'Thiếu tham số!',
            ];
        }
        $content = $this->findModel($content_id);
        if (!$content) {
            return [
                'success' => false,
                'message' => 'Không thấy nội dung!',
                'error' => 'Không thấy nội dung!',
            ];
        } else {
            $index = -1;
            $images = Product::convertJsonToArray($content->images);
            Yii::trace($images);
            foreach ($images as $key => $row) {
                if ($row['name'] == $name) {
                    $index = $key;
                }
            }
            if ($index == -1) {
                return [
                    'success' => false,
                    'message' => 'Không thấy ảnh!',
                    'error' => 'Không thấy ảnh!',
                ];
            } else {
                array_splice($images, $index, 1);
                Yii::trace($images);
                $content->images = Json::encode($images);
                if ($content->save(false)) {
                    return [
                        'success' => true,
                        'message' => 'Xóa ảnh thành công',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $content->getErrors(),
                    ];
                }
            }
        }

    }

    public function actionUploadFile($id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Product();
        $type = Yii::$app->request->post('type');
        $allowExt = ['png', 'jpg', 'jpeg', 'gif'];
        if ($type == Product::IMAGE_TYPE_THUMBNAIL) {
            $old_value = Yii::$app->request->post('thumbnail_old');
            $attribute = 'thumbnail';
        } elseif ($type == Product::IMAGE_TYPE_SCREENSHOOT) {
            $old_value = Yii::$app->request->post('screenshot_old');
            $attribute = 'screenshoot';
        } else {
            $old_value = Yii::$app->request->post('thumbnail_old');
            $attribute = 'thumbnail';
        }
        $model->load(Yii::$app->request->post());

        $files = null;

        if (empty($_FILES['Product'])) {
            return []; // or process or throw an exception
        }

        $files = $_FILES['Product'];
        Yii::trace($type . '  ' . $attribute);
        $file_type = '';
        list($width, $height, $file_type, $attr) = getimagesize($files['tmp_name']["$attribute"][0]);
        Yii::info($width . 'xxx' . $height);

        Yii::info($files);
        $new_file = [];
        $size = $files['size']["$attribute"][0];
        $ext = explode('.', basename($files['name']["$attribute"][0]));
        $checkExt = $ext[max(array_keys($ext))];
        $file_name = uniqid() . time() . '.' . array_pop($ext);
        $uploadPath = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images');
        $target = $uploadPath . DIRECTORY_SEPARATOR . $file_name;
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777);
        }

        if (!in_array($checkExt, $allowExt)) {
            return ['success' => false, 'error' => Yii::t('app', "Ảnh không đúng định dạng")];
        }

        if ($size > Product::MAX_SIZE_UPLOAD) {
            return ['success' => false, 'error' => Yii::t('app', "Ảnh vượt quá dung lượng cho phép")];
        }

        if (move_uploaded_file($files['tmp_name']["$attribute"][0], $target)) {
            $success = true;
            $new_file['name'] = $file_name;
            $new_file['type'] = $type;
            $new_file['size'] = $size;
        } else {
            $success = false;
        }
        // neu tao file thanh cong. tra ve danh sach file moi
        if ($success) {
            if ($id === null) {
                $output = ['success' => $success, 'output' => json_encode($new_file)];

                return $output;
            }

            $oldImages = Product::findOne($id);
            // var_dump(json_decode($oldImages->images, true));die;

            if ($type == Product::IMAGE_TYPE_THUMBNAIL) {
                $imgs = Product::convertJsonToArray($oldImages->images, true) !== null ? Product::convertJsonToArray($oldImages->images, true) : [];
                $imgs = array_filter($imgs, function ($v) {
                    return $v['type'] != Product::IMAGE_TYPE_THUMBNAIL;
                });

                $oldImages->images = json_encode(array_merge($imgs, [$new_file]));
            } else {
                $oldImages->images = json_encode(array_merge(Product::convertJsonToArray($oldImages->images, true) !== null ? Product::convertJsonToArray($oldImages->images, true) : [], [$new_file]));
            }

            $success = $oldImages->update();

            $old_value = Product::convertJsonToArray($old_value);
            $old_value[] = $new_file;
        }
        $output = ['success' => $success, 'output' => $oldImages->images];

        return $output;
    }

    public function actionUpdateStatusContent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $cp = Yii::$app->user->id;

        if (isset($post['ids']) && isset($post['newStatus'])) {
            $ids = $post['ids'];
            $newStatus = $post['newStatus'];
            $contents = Product::findAll($ids);
            $count = 0;

            foreach ($contents as $content) {
                if ($content->spUpdateStatus($newStatus)) {
                    ++$count;
                }
            }

            $successMess = $newStatus == Product::STATUS_DELETE ? Yii::t('app', 'Xóa') : Yii::t('app', 'Cập nhật');

            return [
                'success' => true,
                'message' => $successMess . ' ' . $count . Yii::t('app', ' sản phẩm thành công!'),
            ];
        } else {
            return [
                'success' => false,
                'message' => Yii::t('app', 'Không thành công. Vui lòng thử'),
            ];
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
                $content = Product::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($content || $model->id != $content->id) {
                    $content->load($post['Product'][$index], '');
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
}

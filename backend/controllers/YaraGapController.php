<?php

namespace backend\controllers;

use Yii;
use common\models\YaraGap;
use common\models\YaraGapSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * YaraGapController implements the CRUD actions for YaraGap model.
 */
class YaraGapController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all YaraGap models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new YaraGapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single YaraGap model.
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
     * Creates a new YaraGap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new YaraGap();

        if ($model->load(Yii::$app->request->post())) {

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@news_image') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($image->saveAs($tmp . $file_name)) {
                    $model->image = $file_name;
                }
            }

            $model->created_at = time();
            $model->updated_at = time();
            $model->save(false);
            \Yii::$app->getSession()->setFlash('success', 'Thêm mới thành công');

            return $this->redirect(['index']);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing YaraGap model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldImg = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@news_image') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }

                if ($image->saveAs($tmp . $file_name)) {
                    $model->image = $file_name;
                }
            } else {
                $model->image = $oldImg;
            }
            $model->updated_at = time();
            $model->save(false);

            \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');

            return $this->redirect(['index']);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing YaraGap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the YaraGap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return YaraGap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = YaraGap::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateOrder($id)
    {
        $model = $this->findModel($id);
        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                $new = YaraGap::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($new || $model->id != $new->id) {
                    $new->load($post['YaraGap'][$index], '');
                    if ($new->update()) {
                        // tao log

                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    } else {
                        $description = 'UPDATE ORDER CONTENT';
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

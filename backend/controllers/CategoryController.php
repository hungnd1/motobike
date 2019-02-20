<?php

namespace backend\controllers;

use common\models\Category;
use common\models\CategorySearch;
use common\models\ImportDeviceForm;
use common\models\LogData;
use PHPExcel_IOFactory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
                    'get-frui'=>['POST']
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex($type = Category::TYPE_GAP_GOOD)
    {
        $searchModel = new CategorySearch();
        $params = Yii::$app->request->queryParams;
        $params['CategorySearch']['type'] = $type;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = Category::TYPE_GAP_GOOD)
    {
        $model = new Category();
        $model->type = $type;
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
            $model->save();
            return $this->redirect(['index', 'type' => $type]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $model->type
            ]);
        }
    }

    /**
     * Updates an existing Category model.
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
            $model->save();
            return $this->redirect(['index','type'=>$model->type]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetFruit(){

        if ($id = Yii::$app->request->post('id')) {
            $rows = Category::find()->andWhere(['status' => Category::STATUS_ACTIVE])->andWhere(['fruit_id'=>$id])->all();
            echo "<option value='0'>-- Chọn danh mục --</option>";
            foreach ($rows as $operation)
                /** @var $operation Category */
                    echo "<option value='" . $operation->id . "'>" . $operation->display_name . "</option>";
            } else
                echo "<option>-- Chọn danh mục --</option>";

    }
}

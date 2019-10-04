<?php

namespace backend\controllers;

use PHPExcel_IOFactory;
use Yii;
use common\models\FileManage;
use common\models\FileManageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FileManageController implements the CRUD actions for FileManage model.
 */
class FileManageController extends Controller
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
     * Lists all FileManage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileManageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FileManage model.
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
     * Creates a new FileManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FileManage();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'file');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if(strtolower($image->extension) == 'xlsx' || strtolower($image->extension) == 'xls'){
                    $model->type_extension =  FileManage::EXCEL;
                }else{
                    $model->type_extension = FileManage::PDF;
                }
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@news_image') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($image->saveAs($tmp . $file_name)) {
                    $model->file = $file_name;
                }
            }
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FileManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');
        $oldFile = $model->file;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'file');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if(strtolower($image->extension) == 'xlsx' || strtolower($image->extension) == 'xls'){
                    $model->type_extension =  FileManage::EXCEL;
                }else{
                    $model->type_extension = FileManage::PDF;
                }
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@news_image') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($image->saveAs($tmp . $file_name)) {
                    $model->file = $file_name;
                }
            }else{
                $model->file = $oldFile;
            }
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FileManage model.
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
     * Finds the FileManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FileManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

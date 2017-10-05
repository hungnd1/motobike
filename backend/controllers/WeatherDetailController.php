<?php

namespace backend\controllers;

use common\models\ImportDeviceForm;
use PHPExcel_IOFactory;
use Yii;
use common\models\WeatherDetail;
use common\models\WeatherDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * WeatherDetailController implements the CRUD actions for WeatherDetail model.
 */
class WeatherDetailController extends Controller
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
     * Lists all WeatherDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WeatherDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WeatherDetail model.
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
     * Creates a new WeatherDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WeatherDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WeatherDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WeatherDetail model.
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
     * Finds the WeatherDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WeatherDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WeatherDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport()
    {
        $model = new ImportDeviceForm();
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'uploadedFile');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    if (sizeof($sheetData) > 0) {
                        foreach ($sheetData as $row) {
                            $rowA = strtotime(str_replace('Z','',str_replace('T',' ',trim($row['A']))));
                        }

                        Yii::$app->getSession()->setFlash('success', Yii::t("app", "Đã import thành công"));
//                        return $this->actionIndex();
                        return $this->redirect(['index']);
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t("app", "Có lỗi xảy ra trong quá trình upload. Vui lòng thử lại"));
                $model = new ImportDeviceForm();
                return $this->render('import', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('import_weather', [
                'model' => $model,
            ]);
        }
    }
}

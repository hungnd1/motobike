<?php

namespace backend\controllers;

use common\models\Category;
use common\models\CategorySearch;
use common\models\ImportDeviceForm;
use common\models\Province;
use common\models\Station;
use PHPExcel_IOFactory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        CategoryController::import();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post())) {
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
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
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

    public function import()
    {
        $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . 'Locations_Data_Lizard_072017.xlsx');
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        if (sizeof($sheetData) > 0) {
            $dataStarted = false;
            $idx = 0;
            $macArr = [];
            $errorsArr = [];
            foreach ($sheetData as $row) {
                $idx++;
                if ($row['A'] == 'CODE') {
                    $dataStarted = true;
                    continue;
                }
                if (!$dataStarted) {
                    continue;
                }
                $station = new Station();
                $station->station_code = $this->getImportedValue(Station::STATION_CODE, $row['A']);
                $station->station_name = $this->getImportedValue(Station::STATION_NAME, $row['G']);
                $station->com_code = $this->getImportedValue(Station::COM_CODE, $row['D']);
                $station->district_name = $this->getImportedValue(Station::DISTRICT_NAME, $row['F']);
                $station->district_code = $this->getImportedValue(Station::DISTRICT_CODE, $row['C']);
                $station->province_id = $this->getImportedValue(Station::PROVINCE_ID, $row['B']);
                $station->status = Station::STATUS_ACTIVE;
                $station->save(false);
            }
        }
    }
//
    private function getImportedValue($attr, $value)
    {
        $value = trim($value);
        switch ($attr) {
            case Station::STATION_CODE:
                return $value;
            case Station::STATION_NAME:
                return $value;
            case Station::DISTRICT_CODE:
                return $value;
            case Station::COM_CODE:
                return $value;
            case Station::DISTRICT_NAME:
                return $value;
            case Station::PROVINCE_ID;
                $province = Province::findOne(['province_code'=>$value])->id;
                return $province;

        }
        return $value;
    }

}

<?php

namespace backend\controllers;

use common\models\District;
use common\models\ImportDeviceForm;
use common\models\PriceCoffee;
use common\models\PriceCoffeeSearch;
use PHPExcel_IOFactory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * PriceCoffeeController implements the CRUD actions for PriceCoffee model.
 */
class PriceCoffeeController extends Controller
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
     * Lists all PriceCoffee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PriceCoffeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PriceCoffee model.
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
     * Creates a new PriceCoffee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PriceCoffee();

        if ($model->load(Yii::$app->request->post())) {
            $model->province_id = District::findOne(['id' => $model->district_id])->province_id;
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PriceCoffee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PriceCoffee model.
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
     * Finds the PriceCoffee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PriceCoffee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PriceCoffee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImportIndex()
    {
        $searchModel = new PriceCoffeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
                            $coffee_old= PriceCoffee::findOne(['organisation_name'=>trim($row['B']),'province_id'=>trim($row['D']),'created_at'=>$rowA]);
                            if(!$coffee_old){
                                $price = new PriceCoffee();
                                $price->province_id = trim($row['D']);
                                $price->price_average = trim($row['C']);
                                $price->unit = PriceCoffee::UNIT_VND;
                                $price->created_at = $rowA;
                                $price->updated_at = $rowA;
                                $price->last_time_value = $rowA;
                                $coffee_old_id = PriceCoffee::findOne(['organisation_name'=>$row['B'],'province_id'=>$row['D']]);
                                if($coffee_old_id){
                                    $price->coffee_old_id = $coffee_old_id->coffee_old_id;
                                }
                                $price->organisation_name = trim($row['B']);
                                $price->save();
                            }

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
            return $this->render('import', [
                'model' => $model,
            ]);
        }
    }
}

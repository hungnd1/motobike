<?php

namespace backend\controllers;

use common\models\Category;
use common\models\CategorySearch;
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

//    public function actionImport()
//    {
//        $model = new ImportDeviceForm();
//        if ($model->load(Yii::$app->request->post())) {
//            $file = UploadedFile::getInstance($model, 'uploadedFile');
//            if ($file) {
//                $file_name = uniqid() . time() . '.' . $file->extension;
//                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
//                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
//                    $sheetData   = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
//                    if (sizeof($sheetData) > 0) {
//                        $dataStarted = false;
//                        $idx         = 0;
//                        $macArr      = [];
//                        $errorsArr   = [];
//                        foreach ($sheetData as $row) {
//                            $idx++;
//                            if ($row['A'] == 'STT') {
//                                $dataStarted = true;
//                                continue;
//                            }
//                            if (!$dataStarted) {
//                                continue;
//                            }
//                            if ($dataStarted && $row['D'] == '') {
//                                break;
//                            }
//                            $order  = $this->getImportedValue('ORDER', $row['A']);
//                            $errors = [];
//                            $mac    = trim($row['D']);
////                            if (in_array($mac, $macArr)) {
////                                $errors[Device::IPT_MAC] = Yii::t("app","Địa chỉ MAC bị lặp lại trong file import");
////                            }
//                            $dealerCode = $this->getImportedValue(Device::IPT_DEALER, $row['E']);
////                            if ($dealerCode) {
////                                $dealer = Dealer::findOne(['code' => strtoupper(trim($dealerCode))]);
////                                if (!$dealer) {
////                                    $errors[Device::IPT_DEALER] = Yii::t("app","Mã đại lý "). $dealerCode. Yii::t("app"," không tồn tại.");
////                                } else if ($dealer->status != Dealer::STATUS_ACTIVE) {
////                                    $errors[Device::IPT_DEALER] = Yii::t("app","Mã đại lý"). $dealerCode.Yii::t("app"," đã ngừng hoạt động.");
////                                }
////                            }
//                            $device                  = new Device();
//                            $device->site_id         = $this->sp_user->site_id;
//                            $device->device_type     = $this->getImportedValue(Device::IPT_DEVICE_TYPE, $row['B']);
//                            $device->device_firmware = $this->getImportedValue(Device::IPT_FIRMWARE, $row['C']);
//                            $device->device_id       = $this->getImportedValue(Device::IPT_MAC, $row['D']);
//                            $device->status          = $this->getImportedValue(Device::IPT_STATUS, $row['F']);
//                            if ($dealer) {
//                                $device->dealer_id = $dealer->id;
//                            }
//                            if ($device->validate() && empty($errors)) {
//                                $macArr[]     = $mac;
//                                $devicesLst[] = $device->attributes;
//                            } else {
//                                foreach ($device->errors as $attr => $messagesArr) {
//                                    if (isset($errors[$attr])) {
//                                        continue;
//                                    }
//                                    $messages = '';
//                                    foreach ($messagesArr as $msg) {
//                                        $messages = $messages . "$msg.";
//                                    }
//                                    $errors[$attr] = $messages;
//                                }
//                            }
//                            if (!empty($errors)) {
//                                $errorsArr[$order] = $errors;
//                            }
//                        }
//                        Yii::trace($errorsArr);
//                        if ($errorsArr) {
//                            $objPHPExcel = new PHPExcel;
//                            $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
//                            $objSheet    = $objPHPExcel->getActiveSheet();
//                            $objSheet->getCell('A1')->setValue(Yii::t("app","Thiết bị"));
//                            $objSheet->mergeCells('A1:F2');
//
//                            // headers
//                            $objSheet->getCell('A3')->setValue(Yii::t("app","STT"));
//                            $objSheet->getCell('B3')->setValue(Yii::t("app","Loại thiết bị"));
//                            $objSheet->getCell('C3')->setValue(Yii::t("app","Firmware"));
//                            $objSheet->getCell('D3')->setValue(Yii::t("app","Địa chỉ MAC"));
//                            $objSheet->getCell('E3')->setValue(Yii::t("app","Mã đại lý"));
//                            $objSheet->getCell('F3')->setValue(Yii::t("app","Trạng thái"));
//
//                            // data
//                            $rowOrderError = 4;
//                            foreach ($errorsArr as $order => $errors) {
//                                $objSheet->getCell($this->getCell(Device::IPT_ORDER, $rowOrderError))->setValue($order);
//                                foreach ($errors as $attr => $error) {
//                                    $objSheet->getCell($this->getCell($attr, $rowOrderError))->setValue($error);
//                                }
//                                $rowOrderError++;
//                            }
//
//                            // autosize the columns
//                            $objSheet->getColumnDimension('A')->setAutoSize(true);
//                            $objSheet->getColumnDimension('B')->setAutoSize(true);
//                            $objSheet->getColumnDimension('C')->setAutoSize(true);
//                            $objSheet->getColumnDimension('D')->setAutoSize(true);
//                            $objSheet->getColumnDimension('E')->setAutoSize(true);
//                            $objSheet->getColumnDimension('F')->setAutoSize(true);
//
//                            $error_file_name = basename($file_name) . '_err.' . $file->extension;
//                            $objWriter->save(Yii::getAlias('@excel_folder') . "/" . $error_file_name);
//                            Yii::$app->getSession()->setFlash('error', Yii::t("app","Có lỗi xảy ra trong quá trình upload. Vui lòng tải liên kết bên dưới và xem chi tiết lỗi trong file"));
//                            $model            = new ImportDeviceForm();
//                            $model->errorFile = Yii::getAlias('@excel_folder') . "/" . $error_file_name;
//                            return $this->render('import', [
//                                'model' => $model,
//                            ]);
//                        }
//                        $count = 0;
//                        if (isset($devicesLst) && !empty($devicesLst)) {
//                            Yii::$app->db->createCommand()->batchInsert(Device::tableName(), (new Device())->attributes(), $devicesLst)->execute();
//                            $count = count($devicesLst);
//                        }
//                        Yii::$app->getSession()->setFlash('success', Yii::t("app","Đã import thành công"). $count.Yii::t("app"," thiết bị."));
////                        return $this->actionIndex();
//                        return $this->redirect(['index']);
//                    }
//                }
//            } else {
//                Yii::$app->getSession()->setFlash('error', Yii::t("app","Có lỗi xảy ra trong quá trình upload. Vui lòng thử lại"));
//                $model = new ImportDeviceForm();
//                return $this->render('import', [
//                    'model' => $model,
//                ]);
//            }
//        } else {
//            return $this->render('import', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    private function getImportedValue($attr, $value)
//    {
//        $value = trim($value);
//        switch ($attr) {
//            case Device::IPT_DEVICE_TYPE:
//                if (!in_array($value, Device::getListAvailableDeviceTypesValue())) {
//                    return Device::TYPE_SMARTBOXV2;
//                }
//                return $value;
//            case Device::IPT_STATUS:
//                if (!in_array($value, Device::getListAvailableStatusesValue())) {
//                    return Device::STATUS_NEW;
//                }
//                return $value;
//            case Device::IPT_MAC:
//                return strtoupper($value);
//            case Device::IPT_DEALER:
//                return strtoupper($value);
//        }
//        return $value;
//    }
//
//    private function getCell($attr, $rowIdx)
//    {
//        switch ($attr) {
//            case Device::IPT_ORDER:
//                return "A$rowIdx";
//            case Device::IPT_DEVICE_TYPE:
//                return "B$rowIdx";
//            case Device::IPT_FIRMWARE:
//                return "C$rowIdx";
//            case Device::IPT_MAC:
//                return "D$rowIdx";
//            case Device::IPT_DEALER;
//                return "E$rowIdx";
//            case Device::IPT_STATUS:
//                return "F$rowIdx";
//            case Device::IPT_NEW_MAC:
//                return "G$rowIdx";
//        }
//        return '';
//    }

}

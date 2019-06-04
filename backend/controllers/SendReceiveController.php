<?php

namespace backend\controllers;

use api\helpers\APIHelper;
use common\helpers\CUtils;
use common\models\MtTemplate;
use PHPExcel_IOFactory;
use Yii;
use common\models\SendReceive;
use common\models\SendReceiveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SendReceiveController implements the CRUD actions for SendReceive model.
 */
class SendReceiveController extends Controller
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
     * Lists all SendReceive models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SendReceiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SendReceive model.
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
     * Creates a new SendReceive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SendReceive();
        if ($model->load(Yii::$app->request->post())) {
            $mtTem = MtTemplate::findOne($model->mt_template_id)->content;
            if ($model->import == 1) {
                $lstTo = $model->to;
                $lstPhone = explode(",", $model->to);
                if (sizeof($lstPhone) >= 1) {
                    for ($i = 0; $i < sizeof($lstPhone); $i++) {
                        $modelNew = new SendReceive();
                        $phoneNumber = CUtils::validateMobile($lstPhone[$i]);
                        if ($phoneNumber) {
                            $modelNew->to = $phoneNumber;
                        } else {
                            $modelNew->to = $lstPhone[$i];
                        }
                        $modelNew->from = Yii::$app->params['brandName'];
                        $modelNew->text = $mtTem;
                        $modelNew->created_at = time();
                        $modelNew->updated_at = time();
                        $modelNew->save(false);
//                    $data = array(['from'=>Yii::$app->params['brandName'],'to'=>$phoneNumber,'text'=>$mtTem]);
                        $data = '
                    {
                      "from": "' . Yii::$app->params['brandName'] . '",
                      "to": "' . $phoneNumber . '",
                      "text": "' . $mtTem . '"
                     }';
                        $result = APIHelper::apiQueryV1("POST", Yii::$app->params['urlSms'], $data, Yii::$app->params['Authorization']);
                        if (isset($result)) {
                            $arr = json_decode($result, true);
                            if ($arr['status'] == 1) {
                                $arr = json_decode($result, true);
                                $modelNew->status = $arr['status'];
                                $modelNew->carrier = isset($arr['carrier']) ? $arr['carrier'] : '';
                            } else {
                                $modelNew->status = $arr['status'];
                                $modelNew->carrier = isset($arr['carrier']) ? $arr['carrier'] : '';
                                $modelNew->error_code = $arr['errorcode'];
                                $modelNew->description = $arr['description'];
                            }
                            $modelNew->save();
                        }
                    }
                }
                $model->to = $lstTo;
            } else {
                $file = UploadedFile::getInstance($model, 'fileUpload');
                if ($file) {
                    $file_name = uniqid() . time() . '.' . $file->extension;
                    if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@excel_folder') . "/" . $file_name)) {
                        $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@excel_folder') . "/" . $file_name);
                        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                        $first = 0;
                        if (sizeof($sheetData) > 0) {
                            foreach ($sheetData as $row) {
                                if ($first > 1) {
                                    $modelNew = new SendReceive();
                                    $phoneNumber = trim($row['A']);
                                    $phoneNumber = CUtils::validateMobile($phoneNumber);
                                    if ($phoneNumber) {
                                        $modelNew->to = $phoneNumber;
                                        $modelNew->from = Yii::$app->params['brandName'];
                                        $modelNew->text = $mtTem;
                                        $modelNew->created_at = time();
                                        $modelNew->updated_at = time();
                                        $modelNew->save(false);
                                        $data = '
                                        {
                                          "from": "' . Yii::$app->params['brandName'] . '",
                                          "to": "' . $phoneNumber . '",
                                          "text": "' . $mtTem . '"
                                         }';
                                        $result = APIHelper::apiQueryV1("POST", Yii::$app->params['urlSms'], $data, Yii::$app->params['Authorization']);
                                        if (isset($result)) {
                                            $arr = json_decode($result, true);
                                            if ($arr['status'] == 1) {
                                                $arr = json_decode($result, true);
                                                $modelNew->status = $arr['status'];
                                                $modelNew->carrier = isset($arr['carrier']) ? $arr['carrier'] : '';
                                            } else {
                                                $modelNew->status = $arr['status'];
                                                $modelNew->carrier = isset($arr['carrier']) ? $arr['carrier'] : '';
                                                $modelNew->error_code = $arr['errorcode'];
                                                $modelNew->description = $arr['description'];
                                            }
                                            $modelNew->save();
                                        }
                                    }
                                }
                                $first++;
                            }
                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t("app", "Có lỗi xảy ra trong quá trình import. Vui lòng thử lại"));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

            }
            $model->isNewRecord = true;
            Yii::$app->session->setFlash('success', 'Gửi tin nhắn thành công');
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SendReceive model.
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
     * Deletes an existing SendReceive model.
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
     * Finds the SendReceive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SendReceive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SendReceive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

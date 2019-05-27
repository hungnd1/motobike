<?php

namespace backend\controllers;

use api\helpers\APIHelper;
use common\helpers\CUtils;
use common\models\MtTemplate;
use Yii;
use common\models\SendReceive;
use common\models\SendReceiveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            $lstTo = $model->to;
            $lstPhone = explode(",",$model->to);
            $mtTem = MtTemplate::findOne($model->mt_template_id)->content;
            if(sizeof($lstPhone)>= 1){
                for($i=0; $i < sizeof($lstPhone); $i++ ){
                    $phoneNumber = CUtils::validateMobile($lstPhone[$i]);
                    if($phoneNumber){
                        $model->to = $phoneNumber;
                    }else{
                        $model->to = $lstPhone[$i];
                    }
                    $model->from = Yii::$app->params['brandName'];
                    $model->text = $mtTem;
                    $model->created_at = time();
                    $model->updated_at = time();
                    $model->save(false);
//                    $data = array(['from'=>Yii::$app->params['brandName'],'to'=>$phoneNumber,'text'=>$mtTem]);
                    $data = '
                    {
                      "from": "' . Yii::$app->params['brandName'].'",
                      "to": "' . $phoneNumber . '",
                      "text": "' . $mtTem . '"
                     }';
                    $result = APIHelper::apiQueryV1("POST",Yii::$app->params['urlSms'],$data,Yii::$app->params['Authorization']);
                    if(isset($result)){
                        $arr = json_decode($result,true);
                        if($arr['status'] == 1){
                            $arr = json_decode($result,true);
                            $model->status = $arr['status'];
                            $model->carrier = isset($arr['carrier']) ? $arr['carrier'] : '';
                        }else{
                            $model->status = $arr['status'];
                            $model->carrier = isset($arr['carrier']) ? $arr['carrier'] : '';
                            $model->error_code = $arr['errorcode'];
                            $model->description = $arr['description'];
                        }
                        $model->save();
                    }
                }
            }
            $model->to = $lstTo;
            $model->isNewRecord = true;
            Yii::$app->session->setFlash('success','Gửi tin nhắn thành công');
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

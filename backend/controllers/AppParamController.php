<?php

namespace backend\controllers;

use Yii;
use common\models\AppParam;
use common\models\AppParamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppParamController implements the CRUD actions for AppParam model.
 */
class AppParamController extends Controller
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
     * Lists all AppParam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppParamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppParam model.
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
     * Creates a new AppParam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AppParam();

        if ($model->load(Yii::$app->request->post())) {
            $existParam = AppParam::find()
                ->andWhere(['param_key'=> $model->param_key])
                ->one();
            if($existParam){
                Yii::$app->session->setFlash('error', 'Key cấu hình đã trùng');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            Yii::$app->session->setFlash('success', 'Thêm mới thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppParam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $existParam = AppParam::find()
                ->andWhere(['param_key'=> $model->param_key])
                ->andWhere(['<>','id',$model->id])
                ->one();
            if($existParam){
                Yii::$app->session->setFlash('error', 'Key cấu hình đã trùng');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $model->updated_at = time();
            $model->save();
            Yii::$app->session->setFlash('success', 'Cập nhật thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AppParam model.
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
     * Finds the AppParam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppParam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppParam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers;

use Yii;
use common\models\GapGeneral;
use common\models\GapGeneralSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GapGeneralController implements the CRUD actions for GapGeneral model.
 */
class GapGeneralController extends Controller
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
     * Lists all GapGeneral models.
     * @return mixed
     */
    public function actionIndex($type = GapGeneral::GAP_GENERAL)
    {
        $searchModel = new GapGeneralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type
        ]);
    }

    /**
     * Displays a single GapGeneral model.
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
     * Creates a new GapGeneral model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = GapGeneral::GAP_GENERAL)
    {
        $model = new GapGeneral();
        if ($model->load(Yii::$app->request->post())) {
            $model->type = $type;
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            \Yii::$app->getSession()->setFlash('success', 'Thêm mới thành công');
            return $this->redirect(['index','type'=>$type]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $type
            ]);
        }
    }

    /**
     * Updates an existing GapGeneral model.
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
            \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'type' => $model->type
            ]);
        }
    }

    /**
     * Deletes an existing GapGeneral model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->getSession()->setFlash('success', 'Xóa thành công');
        return $this->redirect(['index']);
    }

    /**
     * Finds the GapGeneral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GapGeneral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GapGeneral::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

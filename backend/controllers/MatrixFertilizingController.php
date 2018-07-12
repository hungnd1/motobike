<?php

namespace backend\controllers;

use common\models\Answer;
use common\models\Question;
use Yii;
use common\models\MatrixFertilizing;
use common\models\MatrixFertilizingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MatrixFertilizingController implements the CRUD actions for MatrixFertilizing model.
 */
class MatrixFertilizingController extends Controller
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
     * Lists all MatrixFertilizing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatrixFertilizingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MatrixFertilizing model.
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
     * Creates a new MatrixFertilizing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($fruit_id)
    {
        $listQuestion = Question::find()
            ->andWhere(['fruit_id' => $fruit_id])->all();
        $model = new MatrixFertilizing();

        if ($model->load(Yii::$app->request->post())) {
            if($model->id_answer_1){
                $model->answer = $model->id_answer_1;
            }
            if ($model->id_answer_2) {
                $model->answer .= "-" . $model->id_answer_2;
            }
            if ($model->id_answer_3) {
                $model->answer .= "-" . $model->id_answer_3;
            }
            $model->fruit_id = $fruit_id;
            $model->save();
            Yii::$app->session->setFlash('success', 'Thêm mới thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'fruit_id' => $fruit_id,
                'listQuestion' => $listQuestion
            ]);
        }
    }

    /**
     * Updates an existing MatrixFertilizing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $fruit_id)
    {
        $model = $this->findModel($id);
        $listQuestion = Question::find()
            ->andWhere(['fruit_id' => $fruit_id])->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->id_answer_1 && $model->id_answer_2) {
                $model->answer = $model->id_answer_1 . "-" . $model->id_answer_2;
            }
            if ($model->id_answer_3) {
                $model->answer .= "-" . $model->id_answer_3;
            }
            $model->save();
            Yii::$app->session->setFlash('success', 'Cập nhật thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'fruit_id' => $fruit_id,
                'listQuestion' => $listQuestion
            ]);
        }
    }

    /**
     * Deletes an existing MatrixFertilizing model.
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
     * Finds the MatrixFertilizing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MatrixFertilizing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MatrixFertilizing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

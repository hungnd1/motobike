<?php

namespace backend\controllers;

use common\models\Answer;
use Yii;
use common\models\Question;
use common\models\QuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
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
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Question();

        if ($model->load(Yii::$app->request->post())) {
            $model->is_dropdown_list = 1;
            $model->save();
            $answerStr = explode(";", $model->answer);
            for ($i = 0; $i < sizeof($answerStr); $i++) {
                $answer = new Answer();
                $answer->answer = $answerStr[$i];
                $answer->question_id = $model->id;
                $answer->save();
            }
            \Yii::$app->getSession()->setFlash('success', 'Thêm mới thành công');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $listAnswer = Answer::find()->andWhere(['question_id' => $id])->all();
        $answerStr = '';
        $listAns = [];
        foreach ($listAnswer as $item) {
            /** @var $item Answer */
            $answerStr .= $item->id . ":" . $item->answer . ";";
            $listAns[] = $item->id;
        }
        $model->answer = rtrim($answerStr, ";");
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $listArrAnswer = explode(";", $model->answer);
            for ($i = 0; $i < sizeof($listArrAnswer); $i++) {
                $answerEx = explode(":", $listArrAnswer[$i]);
                if (sizeof($answerEx) > 0) {
                    for ($j = 1; $j < sizeof($answerEx); $j++) {
                        $answer = Answer::findOne($answerEx[0]);
                        if ($answer) {
                            if (($key = array_search($answerEx[0], $listAns)) !== false) {
                                unset($listAns[$key]);
                            }
                            $answer->answer = $answerEx[1];
                            $answer->save();
                        } else {
                            $answerNew = new Answer();
                            if ($answerEx[0]) {
                                $answerNew->id = $answerEx[0];
                            }
                            $answerNew->answer = $answerEx[1];
                            $answerNew->question_id = $id;
                            $answerNew->save();
                        }
                    }
                }
            }
            //xoa cau tra loi cua cau hoi
            $answerDelete = Answer::deleteAll(['in', 'id', $listAns]);
            \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Question model.
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
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

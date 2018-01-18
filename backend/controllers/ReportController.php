<?php

namespace backend\controllers;

use backend\models\ReportSubscriberActivityForm;
use backend\models\ReportSubscriberForm;
use common\models\ReportSubscriberActivity;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ReportController implements the CRUD actions for ReportSubscriberActivity model.
 */
class ReportController extends Controller
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
     * Lists all ReportSubscriberActivity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ReportSubscriberActivity::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReportSubscriberActivity model.
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
     * Creates a new ReportSubscriberActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReportSubscriberActivity();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ReportSubscriberActivity model.
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
     * Deletes an existing ReportSubscriberActivity model.
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
     * Finds the ReportSubscriberActivity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReportSubscriberActivity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReportSubscriberActivity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubscriberActivity()
    {
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('d/m/Y');


        $from_date = isset($param['ReportSubscriberActivityForm']['from_date']) ? $param['ReportSubscriberActivityForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportSubscriberActivityForm']['to_date']) ? $param['ReportSubscriberActivityForm']['to_date'] : $to_date_default;

        $report = new ReportSubscriberActivityForm();
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $dataProvider = $report->generateReport();
        $dataProviderAll = $report->generateReportAll1();
        $excelDataProvider = $report->generateDetailReport($dataProviderAll->getModels());
        return $this->render('subscriber-activity', [
            'report' => $report,
            'dataProvider' => $dataProvider,
            'excelDataProvider' => $excelDataProvider
        ]);
    }

    public function actionSubscriberNumber()
    {
        $param = Yii::$app->request->queryParams;
        $to_date_default = (new DateTime('now'))->setTime(23, 59, 59)->format('d/m/Y');
        $from_date_default = (new DateTime('now'))->setTime(0, 0)->modify('-100 days')->format('d/m/Y');


        $from_date = isset($param['ReportSubscriberForm']['from_date']) ? $param['ReportSubscriberForm']['from_date'] : $from_date_default;
        $to_date = isset($param['ReportSubscriberForm']['to_date']) ? $param['ReportSubscriberForm']['to_date'] : $to_date_default;

        $report = new ReportSubscriberForm();
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $dataProvider = $report->generateReport();
        $dataProviderAll = $report->generateReportAll();
        $excelDataProvider = $report->generateDetailReport($dataProviderAll->getModels());
        return $this->render('subscriber-number', [
            'report' => $report,
            'dataProvider' => $dataProvider,
            'excelDataProvider' => $excelDataProvider
        ]);
    }
}

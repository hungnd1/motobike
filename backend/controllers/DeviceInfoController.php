<?php

namespace backend\controllers;

use common\models\DeviceInfo;
use common\models\DeviceInfoSearch;
use common\models\Version;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DeviceInfoController implements the CRUD actions for DeviceInfo model.
 */
class DeviceInfoController extends Controller
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
     * Lists all DeviceInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeviceInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeviceInfo model.
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
     * Creates a new DeviceInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

            /** @var  $version Version */
            $version = Version::find()
                ->andWhere(['id' => 1])->one();
            $version->version = '2.27.0';
            $version->checkLogin = 0;
            $version->save(false);

            /** @var  $version2 Version */
            $version2 = Version::find()
                ->andWhere(['id' => 2])->one();
            $version2->version = '2.70';
            $version2->checkLogin = 0;
            $version2->save(false);
//            $model->save();
            return $this->redirect(['index']);
    }

    /**
     * Updates an existing DeviceInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {

        /** @var  $version Version*/
        $version =  Version::find()
            ->andWhere(['id'=>1])->one();
        $version->version = '2.26.0';
        $version->checkLogin = 1;
        $version->save(false);

        /** @var  $version2 Version*/
        $version2 =  Version::find()
            ->andWhere(['id'=>2])->one();
        $version2->version = '2.60';
        $version2->checkLogin = 1;
        $version2->save(false);


        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing DeviceInfo model.
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
     * Finds the DeviceInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeviceInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeviceInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

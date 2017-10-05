<?php

use common\models\WeatherDetail;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Thời tiết ');
$this->params['breadcrumbs'][] = $this->title;

\common\assets\ToastAsset::register($this);
\common\assets\ToastAsset::config($this, [
    'positionClass' => \common\assets\ToastAsset::POSITION_TOP_RIGHT
]);
?>


<div class="row">
    <div class="col-sm-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span
                        class="caption-subject font-green-sharp bold uppercase"><?= Yii::t('app', 'Thời tiết') ?> </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p><?= Html::a('' . \Yii::t('app', 'Import'), ['import'], ['class' => 'btn btn-success']) ?> </p>
                <?php
                $gridColumn = [
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'station_code',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->station_code;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'precipitation',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->precipitation;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'tmax',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->tmax;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'tmin',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->tmin;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'wnddir',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->wnddir;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'wndspd',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->wndspd;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'clouddc',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->clouddc;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'hprcp',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->hprcp;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'hsun',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->hsun;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'RFTMAX',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->RFTMAX;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'RFTMIN',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\WeatherDetail */
                            return $model->RFTMIN;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'width' => '20%',
                        'label' => 'Thời gian thời tiết',
                        'filterType' => GridView::FILTER_DATE,
                        'attribute' => 'timestamp',
                        'value' => function ($model) {
                            return date('d-m-Y H:i:s', $model->timestamp);
                        }
                    ],
                ];



                $gridColumn[] = [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                ];
                ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'content-index-grid',
                    'filterModel' => $searchModel,
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
//                    'panel' => [
//                        'type' => GridView::TYPE_PRIMARY,
//                        'heading' => Yii::t('app', 'Danh sách thiết bị')
//                    ],
                    'columns' => $gridColumn
                ]); ?>
            </div>
        </div>
    </div>
</div>


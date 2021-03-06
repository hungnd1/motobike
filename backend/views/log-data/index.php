<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý thông tin coffee AKVO');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?= $this->title ?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p><?= Html::a('' . \Yii::t('app', 'Import'), ['import'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'latitude',
                            'format' => 'html',
                            'width' => '15%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\LogData
                                 */
                                return $model->latitude;

                            },
                        ],
                        [
                            'attribute' => 'longitude',
                            'format' => 'html',
                            'width' => '15%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\LogData
                                 */
                                return $model->longitude;

                            },
                        ],
                        [
                            'attribute' => 'content',
                            'format' => 'html',
                            'width' => '45%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\LogData
                                 */
                                return \common\helpers\CUtils::subString($model->content, 200, '...');

                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width' => '20%',
                            'label' => 'Ngày tạo',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'created_at',
                            'value' => function ($model) {
                                return date('d-m-Y H:i:s', $model->created_at);
                            }
                        ],
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
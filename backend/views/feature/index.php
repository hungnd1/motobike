<?php

use common\models\Feature;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FeatureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý đặc điểm cây trồng');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo đặc điểm cây trồng'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'display_name',
                        'order',
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'label' => '' . \Yii::t('app', 'Trạng thái'),
                            'width' => '20%',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Feature
                                 */
                                if ($model->status == Feature::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => Feature::listStatus(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "" . \Yii::t('app', 'Tất cả')],
                        ],

                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['fruit/view', 'id' => $model->id]), [
                                        'title' => '' . \Yii::t('app', 'Thông tin chi tiết'),
                                    ]);

                                },
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
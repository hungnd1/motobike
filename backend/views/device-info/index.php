<?php

use common\models\DeviceInfo;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DeviceInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quản lý thiết bị ');
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
                        class="caption-subject font-green-sharp bold uppercase"><?= Yii::t('app', 'Danh sách thiết bị') ?> </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a('Thêm mới ', Yii::$app->urlManager->createUrl(['device-info/create']), ['class' => 'btn btn-success']) ?>
                </p>
                <?php
                $gridColumn = [
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'device_uid',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\DeviceInfo */
                            return $model->device_uid;
                        },
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'device_type',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\DeviceInfo */
                            return DeviceInfo::$device_type[$model->device_type];
                        },
                        'width' => '150px',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => DeviceInfo::getListType(),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => Yii::t("app", "Tất cả")],
                    ],
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'status',
                        'width' => '200px',
                        'refreshGrid' => true,
                        'editableOptions' => function ($model, $key, $index) {
                            return [
                                'header' => Yii::t('app', 'Trạng thái'),
                                'size' => 'md',
                                'displayValueConfig' => \common\models\DeviceInfo::getListStatusFilter('filter'),
                                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                'data' => \common\models\DeviceInfo::getListStatusFilter('filter'),
                                'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
                                'formOptions' => [
                                    'action' => ['device-info/update-status', 'id' => $model->id]
                                ],
                            ];
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \common\models\DeviceInfo::getListStatusFilter('filter'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],

                        'filterInputOptions' => ['placeholder' => Yii::t('app', 'Tất cả')],
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'width' => '15%',
                        'filterType' => GridView::FILTER_DATE,
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return date('d-m-Y H:i:s', $model->created_at);
                        }
                    ],

                ];


                $gridColumn[] = [
                    'class' => 'kartik\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                Yii::$app->urlManager->createUrl(['device-info/delete', 'id' => $model->id]), [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'data-confirm' => Yii::t('app', 'Bạn có chắc chắn xóa nội dung này?'),
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]);
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


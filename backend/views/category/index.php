<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quản lý danh mục ');
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
                        class="caption-subject font-green-sharp bold uppercase"><?= Yii::t('app', 'Danh sách danh mục') ?> </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a('Thêm mới ', Yii::$app->urlManager->createUrl(['category/create']), ['class' => 'btn btn-success']) ?>
                </p>
                <?php
                $gridColumn = [
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'display_name',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\Category */
                            return $model->display_name;
                        },
                    ],
                    [
                        'format' => 'html',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'description',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\Category */
                            return \common\helpers\CUtils::subString($model->description,150);
                        },
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'order_number',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\Category */
                            return $model->order_number;
                        },
                        'width' => '150px',
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
                                'displayValueConfig' => \common\models\Category::getListStatusFilter('filter'),
                                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                'data' => \common\models\Category::getListStatusFilter('filter'),
                                'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
                                'formOptions' => [
                                    'action' => ['category/update-status', 'id' => $model->id]
                                ],
                            ];
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \common\models\Category::getListStatusFilter('filter'),
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
                    'template' => '{update}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                Yii::$app->urlManager->createUrl(['category/delete', 'id' => $model->id]), [
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


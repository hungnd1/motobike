<?php

use common\helpers\CUtils;
use common\models\QuestionAnswer;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\QuestionAnswerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý hỏi đáp');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo hỏi đáp'), ['create'], ['class' => 'btn btn-success']) ?> </p>
                <?php
                $gridColumns = [
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'format' => 'raw',
                        'label' => 'Ảnh ',
                        'attribute' => 'image',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\QuestionAnswer */
                            $cat_image = Yii::getAlias('@question');
                            return $model->image ? Html::img('@web/' . $cat_image . '/' . $model->image, ['alt' => 'Thumbnail', 'width' => '50', 'height' => '50']) : '';
                        },
                    ],
                    [
                        'attribute' => 'question',
                        'label' => 'Câu hỏi',
                        'format' => 'html',
                        'width' => '20%',
                        'value' => function ($model, $key, $index, $widget) {
                            /**
                             * @var $model \common\models\QuestionAnswer
                             */
                            return CUtils::subString($model->question, 150, '...');

                        },
                    ],
                    [
                        'attribute' => 'answer',
                        'label' => 'Câu trả lời',
                        'format' => 'html',
                        'width' => '20%',
                        'value' => function ($model, $key, $index, $widget) {
                            /**
                             * @var $model \common\models\QuestionAnswer
                             */
                            return CUtils::subString($model->answer, 250, '...');

                        },
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'status',
                        'label' => '' . \Yii::t('app', 'Trạng thái'),
//                'width'=>'180px',
                        'width' => '20%',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            /**
                             * @var $model \common\models\Subscriber
                             */
                            if ($model->status == QuestionAnswer::STATUS_ACTIVE) {
                                return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                            } else {
                                return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                            }

                        },
                        'filter' => QuestionAnswer::listStatus(),
                        'filterType' => GridView::FILTER_SELECT2,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => "" . \Yii::t('app', 'Tất cả')],
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
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['QuestionAnswer/view', 'id' => $model->id]), [
                                    'title' => '' . \Yii::t('app', 'Thông tin chi tiết'),
                                ]);

                            },
//                                'update' => function ($url, $model) {
//                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update', 'id' => $model->id]), [
//                                        'title' => '' . \Yii::t('app', 'Cập nhật thông tin user'),
//                                    ]);
//                                },
//                                'delete' => function ($url, $model) {
////                        Nếu là chính nó thì không cho thay đổi trạng thái
//                                    if ($model->id != Yii::$app->user->getId()) {
//                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['user/delete', 'id' => $model->id]), [
//                                            'title' => '' . \Yii::t('app', 'Xóa user'),
//                                            'data-confirm' => Yii::t('app', 'Xóa người dùng này?')
//                                        ]);
//                                    }
//                                }
                        ]
                    ],
                ]
                ?>
                <?php
                $expMenu = ExportMenu::widget([
                    'dataProvider' => $excelDataProvider,
                    //'columns' => $gridColumns,
                    'showConfirmAlert' => false,
                    'fontAwesome' => true,
                    'showColumnSelector' => true,
                    'dropdownOptions' => [
                        'label' => '' . \Yii::t('app', 'All'),
                        'class' => 'btn btn-default'
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_EXCEL_X => [
                            'label' => 'Excel',
                        ],
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_EXCEL => false,
                    ],
                    'target' => ExportMenu::TARGET_SELF,
                    'filename' => "Report"
                ]) ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'showPageSummary' => true,
                    'columns' => $gridColumns,
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                    ],
                    'toolbar' => [
                        '{export}',
                        $expMenu,
                        ['content' =>
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                                'data-pjax' => 0,
                                'class' => 'btn btn-default',
                                'title' => Yii::t('kvgrid', 'Reset Grid')
                            ])
                        ],
                    ],
                    'export' => [
                        'label' => "Page",
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                        'target' => GridView::TARGET_BLANK,

                    ],
                    'exportConfig' => [
                        GridView::EXCEL => ['label' => 'Excel', 'filename' => "Report"],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
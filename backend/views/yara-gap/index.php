<?php

use common\models\News;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\YaraGapSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý GAP');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo GAP'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'title',
                            'label' => 'Tiêu đề',
                            'format' => 'raw',
                            'width' => '20%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\YaraGap
                                 */
                                $action = "news/view";
                                $res = Html::a('<kbd>' . $model->title . '</kbd>', [$action, 'id' => $model->id]);
                                return $res;

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Ảnh đại diện',
                            'attribute' => 'image',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\YaraGap */
                                $cat_image = Yii::getAlias('@news_image');
                                return $model->image ? Html::img('@web/' . $cat_image . '/' . $model->image, ['alt' => 'Thumbnail', 'width' => '50', 'height' => '50']) : '';
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Mô tả ngắn',
                            'attribute' => 'short_description',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\YaraGap */
                                return $model->short_description;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'fruit_id',
                            'width' => '200px',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\YaraGap::getFruits(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => \Yii::t('app', 'Tất cả')],
                            'value' => function ($model, $key, $index) {
                                /** @var $model \common\models\YaraGap */
                                return $model->getFruitName($model->fruit_id);
                            }
                        ],
                        [
                            'class' => 'kartik\grid\EditableColumn',
                            'attribute' => 'order',
                            'refreshGrid' => true,
                            'editableOptions' => function ($model, $key, $index) {
                                return [
                                    'header' => \Yii::t('app', 'Thứ tự'),
                                    'size' => 'md',
                                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                    'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
                                    'formOptions' => [
                                        'action' => ['yara-gap/update-order', 'id' => $model->id]
                                    ],
                                ];
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
                                 * @var $model \common\models\YaraGap
                                 */
                                if ($model->status == News::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => News::listStatus(),
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
                            'template' => '{update} {delete}',
                            'buttons' => [
//                                'update' => function ($url, $model) {
//                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update', 'id' => $model->id]), [
//                                        'title' => '' . \Yii::t('app', 'Cập nhật thông tin user'),
//                                    ]);
//                                },
                                'delete' => function ($url, $model) {
//                        Nếu là chính nó thì không cho thay đổi trạng thái
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['yara-gap/delete', 'id' => $model->id]), [
                                        'title' => '' . \Yii::t('app', 'Xóa user'),
                                        'data-confirm' => Yii::t('app', 'Xóa thông tin này?')
                                    ]);
                                }
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
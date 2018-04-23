<?php

use common\models\Detail;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Hình ảnh chi tiết');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo hình ảnh chi tiết '), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'display_name',
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'format'=>'raw',
                            'label'=>'Ảnh đại diện',
                            'attribute' => 'image',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Detail */
                                $cat_image=  Yii::getAlias('@news_image');
                                return $model->image ? Html::img('@web/'.$cat_image.'/'.$model->image, ['alt' => 'Thumbnail','width'=>'250','height'=>'150']) : '';
                            },
                        ],
                        [
                            'attribute' => 'description',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\helpers\CUtils::subString($model->description,150,'...');

                            },
                        ],
                        [
                            'attribute' => 'harm',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\helpers\CUtils::subString($model->harm,150,'...');

                            },
                        ],
                        [
                            'attribute' => 'reason',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\helpers\CUtils::subString($model->reason,150,'...');

                            },
                        ],
                        [
                            'attribute' => 'prevention',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\helpers\CUtils::subString($model->prevention,150,'...');

                            },
                        ],
                        [
                            'attribute' => 'fruit_id',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\models\Fruit::findOne($model->fruit_id)->name;

                            },
                        ],
                        [
                            'attribute' => 'feature_id',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\models\Feature::findOne($model->feature_id)->display_name;

                            },
                        ],
                        [
                            'attribute' => 'group_id',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Detail
                                 */
                                return \common\models\Group::findOne($model->group_id)->name;

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'label' => '' . \Yii::t('app', 'Trạng thái'),
                            'width' => '20%',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model Detail
                                 */
                                if ($model->status == Detail::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => Detail::listStatus(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "" . \Yii::t('app', 'Tất cả')],
                        ],

                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
//                            'buttons' => [
//                                'view' => function ($url, $model) {
//                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['fruit/view', 'id' => $model->id]), [
//                                        'title' => '' . \Yii::t('app', 'Thông tin chi tiết'),
//                                    ]);
//
//                                },
////                                'update' => function ($url, $model) {
////                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update', 'id' => $model->id]), [
////                                        'title' => '' . \Yii::t('app', 'Cập nhật thông tin user'),
////                                    ]);
////                                },
////                                'delete' => function ($url, $model) {
//////                        Nếu là chính nó thì không cho thay đổi trạng thái
////                                    if ($model->id != Yii::$app->user->getId()) {
////                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['user/delete', 'id' => $model->id]), [
////                                            'title' => '' . \Yii::t('app', 'Xóa user'),
////                                            'data-confirm' => Yii::t('app', 'Xóa người dùng này?')
////                                        ]);
////                                    }
////                                }
//                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
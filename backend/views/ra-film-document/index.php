<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý danh sách film tài liệu');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo film tài liệu'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'title',
                            'format' => 'raw',
                            'width' => '20%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\RaFilmDocument
                                 */
                                return $model->title;

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'fruit_id',
                            'width' => '20%',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\Category::getFruits(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => \Yii::t('app', 'Tất cả')],
                            'value' => function ($model, $key, $index) {
                                /** @var $model \common\models\GapGeneral */
                                return $model->getFruitName($model->fruit_id);
                            }
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
                                 * @var $model \common\models\RaFilmDocument
                                 */
                                if ($model->status == \common\models\Company::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => \common\models\Company::listStatus(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "" . \Yii::t('app', 'Tất cả')],
                        ],
                        [
                            'format' => 'raw',
                            'width' => '20%',
                            'attribute'=>'url',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\RaFilmDocument
                                 */
                                return $model->url ? '<iframe width="350" height="200" src="https://www.youtube.com/embed/' . explode('v=', $model->url)[1] . '" frameborder="0" allowfullscreen></iframe>' : null;

                            },
                        ],
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['app-param/view', 'id' => $model->id]), [
                                        'title' => '' . \Yii::t('app', 'Thông tin chi tiết'),
                                    ]);

                                },
//                                'update' => function ($url, $model) {
//                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update', 'id' => $model->id]), [
//                                        'title' => '' . \Yii::t('app', 'Cập nhật thông tin user'),
//                                    ]);
//                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                        Yii::$app->urlManager->createUrl(['company/delete', 'id' => $model->id]), [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'data-confirm' => Yii::t('app', 'Bạn có chắc chắn xóa nội dung này?'),
                                            'data-method' => 'post',
                                            'data-pjax' => '0',
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
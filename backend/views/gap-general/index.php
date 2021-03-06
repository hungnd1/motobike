<?php

use common\models\GapGeneral;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GapGeneralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if($type == GapGeneral::GAP_GENERAL ){
    $this->title = '' . \Yii::t('app', 'Quản lý tin sâu bệnh');
}elseif($type == GapGeneral::GAP_DETAIL){
    $this->title = '' . \Yii::t('app', 'Quản lý GAP chi tiết');
}else{
    $this->title = ''. Yii::t('app','Quản lý biến đổi thời tiết');
}
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo mới'), ['create','type'=> $type], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'title',
                            'label'=>'Tiêu đề',
                            'format' => 'html',
                            'width' => '30%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\GapGeneral
                                 */
                                return \common\helpers\CUtils::subString($model->title,150,'...');

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
                            'attribute' => 'category_id',
                            'width' => '20%',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\GapGeneral::listCategory(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => \Yii::t('app', 'Tất cả')],
                            'value' => function ($model, $key, $index) {
                                /** @var $model \common\models\GapGeneral */
                                return $model->getCategoryName();
                            }
                        ],
                        [
                            'attribute' => 'order',
                            'label'=>'Sắp xếp',
                            'format' => 'html',
                            'width' => '10%',
                            'visible' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\GapGeneral
                                 */
                                if($model->type == GapGeneral::GAP_GENERAL || $model->type == GapGeneral::CLIMATE_CHANGE){
                                    return false;
                                }
                                return true;

                            },
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\GapGeneral
                                 */
                                return $model->order;

                            },
                        ],
                        [
                            'attribute' => 'gap',
                            'format' => 'html',
                            'width' => '50%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\GapGeneral
                                 */
                                return \common\helpers\CUtils::subString($model->gap,150,'...');

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
                                 * @var $model \common\models\GapGeneral
                                 */
                                if ($model->status == GapGeneral::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => GapGeneral::listStatus(),
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
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
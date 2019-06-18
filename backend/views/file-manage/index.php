<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FileManageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý File RA');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo mới'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'display_name',
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width' => '20%',
                            'label' => 'Danh mục',
                            'attribute' => 'category_id',
                            'value' => function ($model) {
                                /** @var $model \common\models\FileManage */
                                /** @var  $cate \common\models\Category*/
                                $cate = \common\models\Category::find()->andWhere(['type' => \common\models\Category::TYPE_RA])->andWhere(['id'=>$model->category_id])->one();;
                                if($cate){
                                    return $cate->display_name;
                                }else{
                                    return '';
                                }
                            }
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'type',
                            'label' => '' . \Yii::t('app', 'Loại tài liệu'),
//                'width'=>'180px',
                            'width' => '20%',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\FileManage
                                 */
                                if ($model->type == \common\models\FileManage::TYPE_CONFIRM) {
                                    return '<span class="label label-success">' . $model->getTypeName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getTypeName() . '</span>';
                                }

                            },
                            'filter' => \common\models\FileManage::lstType(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "" . \Yii::t('app', 'Tất cả')],
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
                                if ($model->status == \common\models\FileManage::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => \common\models\FileManage::listStatus(),
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
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                        Yii::$app->urlManager->createUrl(['file-manage/delete', 'id' => $model->id]), [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'data-confirm' => Yii::t('app', 'Bạn có chắc chắn xóa nội dung này?'),
                                            'data-method' => 'post',
                                            'data-pjax' => '0',
                                        ]);
                                }
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
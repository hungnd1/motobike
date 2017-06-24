<?php

use common\models\TotalQuality;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TotalQualitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Danh sách tổng sản lượng ');
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
                        class="caption-subject font-green-sharp bold uppercase"><?= Yii::t('app', 'Danh sách tổng sản lượng') ?> </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a('Thêm mới ', Yii::$app->urlManager->createUrl(['total-quality/create']), ['class' => 'btn btn-success']) ?>
                </p>
                <?php
                $gridColumn = [
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'max_total_quality',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\TotalQuality */
                            return $model->max_total_quality;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'min_total_quality',
                        'value' => function ($model, $key, $index) {
                            /** @var $model \common\models\TotalQuality */
                            return $model->min_total_quality;
                        },
                    ],
                ];


                $gridColumn[] = [
                    'class' => 'kartik\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                Yii::$app->urlManager->createUrl(['total-quality/delete', 'id' => $model->id]), [
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


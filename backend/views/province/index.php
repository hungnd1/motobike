<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProvinceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Danh sách tỉnh ');
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
                    <p>
<!--                        --><?php // echo Html::a("Tạo danh mục ", Yii::$app->urlManager->createUrl(['/province/create']), ['class' => 'btn btn-success']) ?>
                    </p>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'id'=>'grid-category-id',
//                    'filterModel' => $searchModel,
                        'responsive' => true,
                        'pjax' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'province_name',
                                'label' => Yii::t('app','Tên tỉnh'),
                                'value'=>function ($model, $key, $index, $widget) {
                                    /** @var $model \common\models\Province */
                                    return $model->province_name;
                                },
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'province_code',
                                'label' => Yii::t('app','Mã tỉnh'),
                                'value'=>function ($model, $key, $index, $widget) {
                                    /** @var $model \common\models\Province */
                                    return $model->province_code;
                                },
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

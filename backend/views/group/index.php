<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý nhóm cây trồng');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo nhóm cây trồng'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'format'=>'raw',
                            'label'=>'Ảnh đại diện',
                            'attribute' => 'image',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Group */
                                $cat_image=  Yii::getAlias('@news_image');
                                return $model->image ? Html::img('@web/'.$cat_image.'/'.$model->image, ['alt' => 'Thumbnail','width'=>'250','height'=>'150']) : '';
                            },
                        ],
                        'name',
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['fruit/view', 'id' => $model->id]), [
                                        'title' => '' . \Yii::t('app', 'Thông tin chi tiết'),
                                    ]);

                                },
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
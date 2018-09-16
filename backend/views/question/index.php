<?php

use common\models\Fruit;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý câu hỏi phân bón');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tạo câu hỏi phân bón'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'question',
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'format' => 'raw',
                            'label' => 'Cây trồng',
                            'attribute' => 'fruit_id',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Question */

                                    return Fruit::findOne($model->fruit_id)->name;
                            },
                        ],
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{answer}  {view} {update} {delete}',
                            'buttons' => [
                                'answer' => function ($url, $model) {
                                    return Html::a('<span class="	glyphicon glyphicon-plus"></span>', Url::toRoute(['matrix-fertilizing/create', 'fruit_id' => $model->fruit_id]), [
                                        'title' => '' . \Yii::t('app', 'Thêm khuyến cáo'),
                                    ]);

                                },
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['fruit/view', 'id' => $model->id]), [
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
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
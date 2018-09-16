<?php

use common\models\Answer;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MatrixFertilizingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý ma trận phân bón');
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'id_answer_1',
                            'format' => 'html',
                            'width' => '30%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\MatrixFertilizing
                                 */
                                return Answer::find()->andWhere(['id' => $model->id_answer_1])->one()->answer;

                            },
                        ],
                        [
                            'attribute' => 'id_answer_2',
                            'format' => 'html',
                            'width' => '30%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\MatrixFertilizing
                                 */
                                if($model->id_answer_2){
                                    return Answer::find()->andWhere(['id' => $model->id_answer_2])->one()->answer;
                                }
                                return "Không có đáp án";

                            },
                        ],
                        [
                            'attribute' => 'content',
                            'format' => 'html',
                            'width' => '50%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\MatrixFertilizing
                                 */
                                return \common\helpers\CUtils::subString($model->content, 150, '...');

                            },
                        ],
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['matrix-fertilizing/update', 'id' => $model->id, 'fruit_id' => $model->fruit_id]), [
                                        'title' => '' . \Yii::t('app', 'Cập nhật thông tin user'),
                                    ]);
                                },
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
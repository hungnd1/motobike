<?php

use common\helpers\CUtils;
use common\models\QuestionAnswer;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '' . \Yii::t('app', 'Quản lý version App');
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
                <p><?= Html::a('' . \Yii::t('app', 'Tao version mới'), ['create'], ['class' => 'btn btn-success']) ?> </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'version',
                            'label'=>'Phiên bản',
                            'format' => 'raw',
                            'width' => '20%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Version
                                 */
                                return $model->version;

                            },
                        ],
                        [
                            'attribute' => 'link',
                            'label'=>'Link',
                            'format' => 'html',
                            'width' => '20%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Version
                                 */
                                return CUtils::subString($model->link, 250,'...');

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'type',
                            'label' => '' . \Yii::t('app', 'Loại thiết bị'),
//                'width'=>'180px',
                            'width' => '20%',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\Version
                                 */
                                if ($model->type == \common\models\Version::TYPE_ANDROID) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => \common\models\Version::listStatus(),
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
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
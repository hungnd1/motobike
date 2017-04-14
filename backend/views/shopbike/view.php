<?php

use kartik\detail\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Shopbike */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' =>Yii::t('app', 'Hãng xe '), 'url' => Yii::$app->urlManager->createUrl(['shopbike/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?php echo $model->username;?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>

                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?= Html::a(Yii::t('app', 'Cập nhật'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Xóa'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Bạn chắc chắn muốn xóa voucher "'.$model->username.'"?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'username',
                        'email',
                        'phone',
                        'address',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => ($model->status == \common\models\Shopbike::STATUS_ACTIVE) ?
                                '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                                '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                            'type' => DetailView::INPUT_SWITCH,
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'onText' => 'Active',
                                    'offText' => 'Delete',
                                ]
                            ]
                        ],
                        [
                            'attribute' => 'time_open',
                            'value' => date('H:i', $model->time_open),
                        ],
                        [
                            'attribute' => 'time_close',
                            'value' => date('H:i', $model->time_close),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

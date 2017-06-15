<?php

use common\models\DeviceInfo;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DeviceInfo */

$this->title = $model->device_uid;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Danh sách thiết bị"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Thông tin thiết bị")?><?= $model->device_uid ?>"</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?= Html::a(Yii::t("app","Cập nhật"), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t("app","Xóa"), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t("app","Bạn chắc chắn muốn xóa thiết bị này không?"),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?php
                        echo DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'device_uid',
                                [
                                    'attribute' => 'device_type',
                                    'value' => \common\models\DeviceInfo::getListStatusNameByType($model->device_type)
                                ],
                                [
                                    'attribute' => 'status',
                                    'value' => \common\models\DeviceInfo::getListStatusNameByStatus($model->status)
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'value' => date('d/m/Y',$model->created_at)
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'value' => date('d/m/Y',$model->updated_at)
                                ],
                            ],
                        ]);
                 ?>
            </div>

        </div>
    </div>
</div>
<?php

use common\models\DeviceInfo;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Station */

$this->title = $model->station_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Danh sách xã"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Thông tin xã ")?><?= $model->station_name ?>"</span>
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
                            'confirm' => Yii::t("app","Bạn chắc chắn muốn xóa xã này không?"),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'station_name',
                        'station_code',
                        'latitude',
                        'latitude',
                        [
                            'attribute' => 'province_id',
                            'value' => \common\models\Station::getProvinceDetail($model->province_id)
                        ],
                        [
                            'attribute' => 'status',
                            'value' => \common\models\Station::getListStatusNameByStatus($model->status)
                        ],
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>
</div>
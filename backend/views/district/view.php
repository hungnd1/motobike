<?php

use common\models\DeviceInfo;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\District */

$this->title = $model->district_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Danh sách huyện"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Thông tin huyện ")?><?= $model->district_name ?>"</span>
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
                            'confirm' => Yii::t("app","Bạn chắc chắn muốn xóa huyện này không?"),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'district_name',
                        [
                            'attribute' => 'province_id',
                            'value' => \common\models\District::getProvinceDetail($model->province_id)
                        ],
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>
</div>
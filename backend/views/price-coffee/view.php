<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PriceCoffee */

$this->title = $model->province_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app", "Danh sách giá"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span
                        class="caption-subject font-green-sharp bold uppercase"><?= Yii::t("app", "Thông tin giá ") ?><?= $model->province_id ?>
                        "</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?= Html::a(Yii::t("app", "Cập nhật"), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t("app", "Xóa"), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t("app", "Bạn chắc chắn muốn xóa nội dung này không?"),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'province_id',
                            'value' => $model->province_id
                        ],
                        [
                            'attribute' => 'organisation_name',
                            'value' => $model->organisation_name
                        ],
                        'price_average',
                        [
                            'attribute' => 'unit',
                            'value' => \common\models\PriceCoffee::getListStatusNameByUnit($model->unit)
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => date('d/m/Y', $model->created_at)
                        ],
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>
</div>
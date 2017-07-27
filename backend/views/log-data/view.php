<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LogData */

$this->title = 'Thông tin AKVO';
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Thông tin coffee AKVO"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Thông tin chi tiết ")?><?= $model->title ?>"</span>
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
                            'confirm' => Yii::t("app","Bạn chắc chắn muốn xóa thông tin này không?"),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'latitude',
                        'longitude',
                        'content:html',
                        [
                            'attribute' => 'type_coffee',
                            'value' => \common\models\TypeCoffee::findOne($model->type_coffee)->name
                        ],
                        [                      // the owner name of the model
                            'attribute'=>'created_at',
                            'label' => ''.\Yii::t('app', 'Ngày tạo'),
                            'value' => date('d/m/Y H:i:s',$model->created_at),
                        ],
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>
</div>
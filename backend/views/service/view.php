<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->service_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Danh sách gói cước"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Gói cước chi tiết ")?><?= $model->service_name ?>"</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'service_name',
                        'description',
                        'price',
                        'time_expired',
                        [
                            'attribute' => 'image',
                            'format' => 'html',
                            'value' => Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@news_image') . "/" . $model->image, ['height' => '200px']),
                        ],
                        [
                            'attribute' => 'status',
                            'value' => \common\models\Service::getListStatusNameByStatus($model->status)
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
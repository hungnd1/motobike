<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Subscriber */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Danh sách tài khoản"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Thông tin tài khoản ")?><?= $model->username ?>"</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                </p>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'username',
                        [
                            'attribute' => 'authen_type',
                            'value' => \common\models\Subscriber::getListStatusNameByType($model->authen_type)
                        ],
                        [
                            'attribute' => 'status',
                            'value' => \common\models\Subscriber::getListStatusNameByStatus($model->status)
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
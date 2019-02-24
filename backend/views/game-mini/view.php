<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\GameMini */

$this->title = $model->question;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Quản lý câu hỏi"), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=Yii::t("app","Câu hỏi chi tiết ")?><?= $model->question ?>"</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?= Html::a(Yii::t("app","Thêm mới câu hỏi"), ['create'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t("app","Cập nhật"), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t("app","Xóa"), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t("app","Bạn chắc chắn muốn xóa câu hỏi này không?"),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'question',
                        'answer_a',
                        'answer_b',
                        'answer_c',
                        'answer_d',
                        'answer_correct',
                        [
                            'attribute' => 'status',
                            'value' => \common\models\News::getListStatusNameByStatus($model->status)
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
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Detail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý thông tin chi tiết', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có muốn xóa nội dung này?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'format'=>'raw',
                'label' => 'Ảnh đại diện',
                'attribute' => 'image',
                'value' => Html::img('@web/' . Yii::getAlias('@news_image') . '/' . $model->image, ['alt' => 'Thumbnail', 'width' => '250', 'height' => '150'])
            ],
            'display_name',
            'description:html',
            'reason:html',
            'harm:html',
            'prevention:html',
            [
                'attribute' => 'status',
                'label' => '' . \Yii::t('app', 'Trạng thái'),
                'format' => 'raw',
                'value' => ($model->status == \common\models\Detail::STATUS_ACTIVE) ?
                    '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                    '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Active',
                        'offText' => 'Delete',
                    ]
                ]
            ],
            [                      // the owner name of the model
                'attribute' => 'created_at',
                'value' => date('d/m/Y H:i:s', $model->created_at),
            ],
        ],
    ]) ?>

</div>

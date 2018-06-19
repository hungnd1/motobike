<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SpecialGapAdvice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Khuyến cáo thông minh', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'tag',
            [
                'attribute' => 'is_question',
                'value' => $model->is_question ? 'Là câu hỏi' : 'Không là câu hỏi'
            ],
            [
                'attribute' => 'status',
                'value' => \common\models\SpecialGapAdvice::getListStatusNameByStatus($model->status)
            ],
            [
                'attribute' => 'fruit_id',
                'value' => \common\models\Fruit::findOne($model->fruit_id)->name
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
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TotalQuality */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Total Qualities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="total-quality-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'min_total_quality',
            'max_total_quality',
        ],
    ]) ?>

</div>

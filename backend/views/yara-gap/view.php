<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\YaraGap */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Yara Gaps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yara-gap-view">

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
            'title',
            'short_description',
            'content:ntext',
            'created_at',
            'updated_at',
            'status',
            'image',
            'order',
            'fruit_id',
        ],
    ]) ?>

</div>

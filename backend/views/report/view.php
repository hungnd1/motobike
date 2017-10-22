<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ReportSubscriberActivity */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Report Subscriber Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-subscriber-activity-view">

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
            'report_date',
            'via_site_daily',
            'total_via_site',
            'via_android',
            'via_website',
        ],
    ]) ?>

</div>

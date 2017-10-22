<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ReportSubscriberActivity */

$this->title = 'Update Report Subscriber Activity: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Report Subscriber Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="report-subscriber-activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

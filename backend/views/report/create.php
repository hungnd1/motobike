<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ReportSubscriberActivity */

$this->title = 'Create Report Subscriber Activity';
$this->params['breadcrumbs'][] = ['label' => 'Report Subscriber Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-subscriber-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

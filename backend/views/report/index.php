<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Subscriber Activities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-subscriber-activity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Report Subscriber Activity', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'report_date',
            'via_site_daily',
            'total_via_site',
            'via_android',
            // 'via_website',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

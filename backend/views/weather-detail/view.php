<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WeatherDetail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Weather Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weather-detail-view">

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
            'station_code',
            'precipitation',
            'tmax',
            'tmin',
            'wnddir',
            'wndspd',
            'station_id',
            'timestamp:datetime',
            'created_at',
            'updated_at',
            'clouddc',
            'hprcp',
            'hsun',
            'RFTMAX',
            'RFTMIN',
        ],
    ]) ?>

</div>

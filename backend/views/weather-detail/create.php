<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WeatherDetail */

$this->title = 'Create Weather Detail';
$this->params['breadcrumbs'][] = ['label' => 'Weather Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weather-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

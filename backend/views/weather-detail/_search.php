<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WeatherDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weather-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'station_code') ?>

    <?= $form->field($model, 'precipitation') ?>

    <?= $form->field($model, 'tmax') ?>

    <?= $form->field($model, 'tmin') ?>

    <?php // echo $form->field($model, 'wnddir') ?>

    <?php // echo $form->field($model, 'wndspd') ?>

    <?php // echo $form->field($model, 'station_id') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'clouddc') ?>

    <?php // echo $form->field($model, 'hprcp') ?>

    <?php // echo $form->field($model, 'hsun') ?>

    <?php // echo $form->field($model, 'RFTMAX') ?>

    <?php // echo $form->field($model, 'RFTMIN') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

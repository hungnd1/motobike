<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WeatherDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weather-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'station_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'precipitation')->textInput() ?>

    <?= $form->field($model, 'tmax')->textInput() ?>

    <?= $form->field($model, 'tmin')->textInput() ?>

    <?= $form->field($model, 'wnddir')->textInput() ?>

    <?= $form->field($model, 'wndspd')->textInput() ?>

    <?= $form->field($model, 'station_id')->textInput() ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'clouddc')->textInput() ?>

    <?= $form->field($model, 'hprcp')->textInput() ?>

    <?= $form->field($model, 'hsun')->textInput() ?>

    <?= $form->field($model, 'RFTMAX')->textInput() ?>

    <?= $form->field($model, 'RFTMIN')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

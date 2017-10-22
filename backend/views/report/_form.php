<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ReportSubscriberActivity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-subscriber-activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'report_date')->textInput() ?>

    <?= $form->field($model, 'via_site_daily')->textInput() ?>

    <?= $form->field($model, 'total_via_site')->textInput() ?>

    <?= $form->field($model, 'via_android')->textInput() ?>

    <?= $form->field($model, 'via_website')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

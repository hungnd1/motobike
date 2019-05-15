<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MoMtSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mo-mt-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'from_number') ?>

    <?= $form->field($model, 'to_number') ?>

    <?= $form->field($model, 'message_mo') ?>

    <?= $form->field($model, 'request_id') ?>

    <?php // echo $form->field($model, 'message_mt') ?>

    <?php // echo $form->field($model, 'status_sync') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

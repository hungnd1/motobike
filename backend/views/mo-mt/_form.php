<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MoMt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mo-mt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message_mo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'request_id')->textInput() ?>

    <?= $form->field($model, 'message_mt')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status_sync')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

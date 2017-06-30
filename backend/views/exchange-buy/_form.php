<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ExchangeBuy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exchange-buy-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subscriber_id')->textInput() ?>

    <?= $form->field($model, 'price_buy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

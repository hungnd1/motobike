<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MatrixFertilizing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="matrix-fertilizing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_answer_1')->textInput() ?>

    <?= $form->field($model, 'id_answer_2')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

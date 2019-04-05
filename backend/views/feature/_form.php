<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Feature */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>
<div class="form-body">

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'display_name')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'order')->textInput(['maxlength' => 10, 'class' => 'input-circle']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\Feature::listStatus(), ['class' => 'input-circle']
            ) ?>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo đặc điểm cây trồng' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

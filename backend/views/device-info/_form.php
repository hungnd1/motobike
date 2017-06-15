<?php

use common\models\DeviceInfo;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeviceInfo */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 8,
    'options' => ['enctype' => 'multipart/form-data'],
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>
<div class="form-body">

    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'device_uid')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?php } else { ?>
        <?= $form->field($model, 'device_uid')->textInput([ 'readonly' => true]) ?>
    <?php } ?>
    <?= $form->field($model, 'device_type')->dropDownList(DeviceInfo::getListType(), ['class' => 'input-circle' ,'readonly' => true,'disabled'=>'disabled' ]) ?>

    <?= $form->field($model, 'status')->dropDownList(
        DeviceInfo::getListStatus(), ['class' => 'input-circle']
    ) ?>


</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo thiết bị') : Yii::t('app', 'Cập nhật'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Quay lại'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

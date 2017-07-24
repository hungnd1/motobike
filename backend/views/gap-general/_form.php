<?php

use common\models\GapGeneral;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GapGeneral */
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
            <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'gap')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'options' => ['rows' => 8],
                'preset' => 'full'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\News::listStatus(), ['class' => 'input-circle']
            ) ?>
        </div>
    </div>
    <?= $form->field($model,'type')->hiddenInput(['value'=> $type])->label(false) ?>
    <?php if($type == \common\models\GapGeneral::GAP_DETAIL){ ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'temperature_max')->textInput()->label('Nhiệt độ') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'evaporation')->textInput()->label('Lượng mưa') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'humidity')->textInput()->label('Độ ẩm') ?>
            </div>
        </div>
    <?php } ?>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo GAP' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

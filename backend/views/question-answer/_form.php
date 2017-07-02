<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\QuestionAnswer */
/* @var $form yii\widgets\ActiveForm */
$showPreview = !$model->isNewRecord && !empty($model->image);
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
            <?= $form->field($model, 'question')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'answer')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'options' => ['rows' => 8],
                'preset' => 'full'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <?php if ($showPreview) { ?>
                <div class="form-group field-category-icon">
                    <div class="col-sm-offset-3 col-sm-5">
                        <?php echo Html::img($model->getImageLink(), ['class' => 'file-preview-image']) ?>
                    </div>
                </div>
            <?php } ?>

            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options' => ['multiple' => true, 'accept' => 'image/*'],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'showUpload' => false,
                    'showPreview' => (!$showPreview) ? true : false,
                ]
            ]); ?>

        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo hỏi đáp' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

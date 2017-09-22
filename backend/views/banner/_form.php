<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
$showPreview = !$model->isNewRecord && !empty($model->banner);
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

            <?php if ($showPreview) { ?>
                <div class="form-group field-category-icon">
                    <div class="col-sm-offset-3 col-sm-5">
                        <?php echo Html::img($model->getImageLink(), ['class' => 'file-preview-image']) ?>
                    </div>
                </div>
            <?php } ?>

            <?= $form->field($model, 'banner')->widget(FileInput::classname(), [
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
            <?= Html::submitButton($model->isNewRecord ? 'Tạo tin tức' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

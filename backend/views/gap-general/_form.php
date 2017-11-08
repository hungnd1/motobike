<?php

use common\models\GapGeneral;
use common\widgets\CKEditor;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GapGeneral */
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
            <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>
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
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'gap')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_8')->widget(CKEditor::className(), [
                'options' => ['rows' =>2 ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_2')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_3')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
                'preset' => 'full'
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_5')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
                'preset' => 'full'
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_4')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
                'preset' => 'full'
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_9')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
                'preset' => 'full'
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_6')->widget(CKEditor::className(), [
                'options' => ['rows' => 4],
                'preset' => 'full'
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content_7')->widget(CKEditor::className(), [
                'options' => ['rows' => 2],
                'preset' => 'full'
            ]);
            ?>
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
            <div class="col-sm-12">
                <?= $form->field($model, 'temperature_max')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'temperature_min')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'windspeed_max')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'windspeed_min')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'precipitation_max')->textInput()?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'precipitation_min')->textInput() ?>
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

<?php

use common\models\Shopbike;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Shopbike */
/* @var $form yii\widgets\ActiveForm */

$showPreview = !$model->isNewRecord && !empty($model->avatar);
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

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?php if($model->isNewRecord){?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?php } ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 200, 'class' => 'input-circle']) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showPreview' => true,
                'overwriteInitial' => false,
                'showRemove' => false,
                'showUpload' => false
            ]
        ]) ?>
    <?php } else { ?>
        <?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'previewFileType' => 'any',
                'initialPreview' => [
                    Html::img(Url::to($model->getFirstImageLink()), ['class' => 'file-preview-image', 'alt' => $model->avatar, 'title' => $model->avatar]),
                ],
                'showPreview' => true,
                'initialCaption' => $model->getFirstImageLink(),
                'overwriteInitial' => true,
                'showRemove' => false,
                'showUpload' => false
            ]
        ]);
    } ?>
    <?php
    echo $form->field($model, 'time_open')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATETIME,
        'displayFormat' => 'H:i',
        'saveFormat' => 'php:U',
        'displayTimezone' => 'Asia/Ho_Chi_Minh',
    ]);
    ?>

    <?php
    echo $form->field($model, 'time_close')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATETIME,
        'displayFormat' => 'H:i',
        'saveFormat' => 'php:U',
        'displayTimezone' => 'Asia/Ho_Chi_Minh',
    ]);
    ?>

    <?= $form->field($model, 'status')->dropDownList(
        Shopbike::getListStatus(), ['class' => 'input-circle']
    ) ?>


</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo nhà xe') : Yii::t('app', 'Cập nhật'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Quay lại'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

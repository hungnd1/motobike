<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS


JS;
$this->registerJs($js, View::POS_END);

?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>
<div class="form-body">
    <div class="row">
        <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    </div>
    <div class="row" id="fileUpload">
        <div class="col-md-12">
            <?= $form->field($model, 'file')->fileInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
        </div>
        <div class="col-md-offset-2 col-md-10 text-left">
            <?= Html::a(Yii::t('app',"Tải file mẫu"), $model->getTemplateFilePrice()) ?>
        </div>
    </div>
    <br>
    <div class="row" id="fileUpload">
        <div class="col-md-12">
            <?= $form->field($model, 'file_company_file')->fileInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
        </div>
        <div class="col-md-offset-2 col-md-10 text-left">
            <?= Html::a(Yii::t('app',"Tải file mẫu"), $model->getTemplateFile()) ?>
        </div>
    </div>
    <div class="row">
        <?= $form->field($model, 'status')->dropDownList(
            \common\models\Company::listStatus(), ['class' => 'input-circle']
        ) ?>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo công ty' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

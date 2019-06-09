<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use common\models\Fruit;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FileManage */
/* @var $form yii\widgets\ActiveForm */
$showPreview = !$model->isNewRecord && !empty($model->image);
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

    <?= $form->field($model, 'display_name')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'file')->fileInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(Category::find()->andWhere(['type' => Category::TYPE_RA])->asArray()->all(), 'id', 'display_name')) ?>
    <?= $form->field($model, 'type')->dropDownList(
        \common\models\FileManage::lstType(), ['class' => 'input-circle']
    ) ?>
    <?= $form->field($model, 'status')->dropDownList(
        \common\models\FileManage::listStatus(), ['class' => 'input-circle']
    ) ?>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo mới') : Yii::t('app', 'Cập nhật'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Quay lại'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

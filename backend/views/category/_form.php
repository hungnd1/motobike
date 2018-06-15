<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use common\models\Fruit;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
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

    <?= $form->field($model, 'display_name')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'order_number')->textInput(['maxlength' => 255, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'status')->dropDownList(
        Category::getListStatus(), ['class' => 'input-circle']
    ) ?>
    <?= $form->field($model, 'fruit_id')->dropDownList(\yii\helpers\ArrayHelper::map(Fruit::find()->asArray()->all(), 'id', 'name')) ?>

</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo danh mục') : Yii::t('app', 'Cập nhật'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Quay lại'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php

use common\models\TypeCoffee;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LogData */
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
            <?= $form->field($model, 'latitude')->textInput() ?>
            <?= $form->field($model, 'longitude')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
                'options' => ['rows' => 8],
                'preset' => 'full'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php $data = ArrayHelper::map(TypeCoffee::find()->asArray()->all(), 'id', 'name') ?>
            <?= $form->field($model, 'type_coffee')->dropDownList(
                $data
            ) ?>
        </div>
    </div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo thông tin' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

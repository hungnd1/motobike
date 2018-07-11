<?php

use common\models\Answer;
use common\models\Category;
use common\models\Question;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MatrixFertilizing */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>
<div class="form-body">
    <?php
    $i = 1;
    foreach($listQuestion as $question){
        /** @var $question Question */
        ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'id_answer_'.$i)->dropDownList(ArrayHelper::map(Answer::find()->andWhere(['question_id'=>$question->id])->asArray()->all(),'id','answer')) ?>
            </div>
        </div>
    <?php $i++; } ?>
    <div class="row">
        <div class="col-md-12">
            <?php  echo $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
                'preset' => 'full',
            ]);
            ?>
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

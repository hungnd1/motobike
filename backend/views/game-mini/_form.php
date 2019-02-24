<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\GameMini */
/* @var $form yii\widgets\ActiveForm */
$showPreview = !$model->isNewRecord && !empty($model->image);

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
        <div class="col-md-12">
            <?= $form->field($model, 'question')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\common\models\Category::find()->andWhere(['type'=>Category::TYPE_GAME])->asArray()->all(), 'id', 'display_name'),
                ['class' => 'form-control fruit', 'prompt' => ' -- Chọn danh mục --']);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'answer_a')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'answer_b')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'answer_c')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'answer_d')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'answer_correct')->textInput()->hint("Câu trả lời là A,B,C,D") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\GameMini::listStatus(), ['class' => 'input-circle']
            ) ?>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo câu hỏi' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

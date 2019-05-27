<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\SendReceive */
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
        <div class="col-md-12">
            <?= $form->field($model, 'to')->textarea(['maxlength' => 500,'rows'=>6, 'class' => 'input-circle']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'mt_template_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\MtTemplate::find()->andWhere(['IN','mo_key',['DK1','DK2','DK3']])->asArray()->all(), 'id', 'mo_key')) ?>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Gửi tin nhắn theo mẫu' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

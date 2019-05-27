<?php

use common\models\Category;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\News */
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
            <?= $form->field($model, 'mo_key')->textInput(['maxlength' => 50, 'class' => 'input-circle']) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px;">
            Số ký tự : <span id="lblcount" class="safe" style="color: green; font-weight: bold">0</span> ( Số tin :
            <span id="counter" class="safe" style="color: green; font-weight: bold">0</span>)
        </div>
        <?= $form->field($model, 'content', ['options' => ['class' => 'col-xs-12',
            'onkeyup' => "countChar();"]])->textarea(['rows' => 6]) ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'station_code')->textInput(['maxlength' => 50, 'class' => 'input-circle']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\MtTemplate::listStatus(), ['class' => 'input-circle']
            ) ?>
        </div>
    </div>
    <br>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo template' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    function insertEmoticonAtTextareaCursor(ID, text) {
        ID = "insertPattern";
        var message = $('textarea#mttemplate-content').val();
        var input = document.getElementById('mttemplate-content'); // or $('#myinput')[0]
        var strPos = 0;
        var br = ((input.selectionStart || input.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false));
        if (br == "ie") {
            input.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -input.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = input.selectionStart;
        var front = (input.value).substring(0, strPos);
        var back = (input.value).substring(strPos, input.value.length);
        input.value = front + text + back;
        strPos = strPos + text.length;
        if (br == "ie") {
            input.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -input.value.length);
            range.moveStart('character', strPos);
            range.moveEnd('character', 0);
            range.select();
        }
        else if (br == "ff") {
            input.selectionStart = strPos;
            input.selectionEnd = strPos;
            input.focus();
        }
        countChar();
    }

    function countChar() {
        var min = 0,
            len = $('#mttemplate-content').val().length,
            lbl = $('#lblcount');
        var ch = 0;
        if (min < 0) {
            lbl.text(0);
        } else {
            ch = min + len;
            lbl.text(ch);
        }
        var sotin = 0;
        if (ch == 0)
            sotin = 0;
        else
            sotin = parseInt(ch) / 160 + 1;
        $('#counter').text(Math.floor(sotin));
    }

</script>

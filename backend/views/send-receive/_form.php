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
$model->import = '1';
$js = <<<JS
    function changeImport(){
        $('input[name="SendReceive[import]"]').change(function() {
        if (this.value == 1){
            $('#to').show();
            $('#fileUpload').hide();
        }else{
            $('#to').hide();
            $('#fileUpload').show();
        }
        });
    }

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
            <?=
            $form->field($model, 'import', [
                'inputOptions' => [
                    'id' => 'motiv'
                ]])
                ->radioList(
                    [1 => 'Nhập tay', 0 => 'Import'],
                    [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $return = '<label class="modal-radio">';
                            $return .= '<input type="radio" checked style="min-height:36px;" name="' . $name . '" value="' . $value . '" tabindex="3">';
                            $return .= '<i></i>';
                            $return .= '<span>' . ucwords($label) . '</span>';
                            $return .= '</label>';

                            return $return;
                        },
                        'onclick' => "changeImport();"
                    ]
                )
                ->label("Chọn kiểu nhắn tin");
            ?>
        </div>
    </div>
    <div class="row" id="to" style="display: none;">
        <div class="col-md-12">
            <?= $form->field($model, 'to')->textarea(['maxlength' => 500, 'rows' => 6, 'class' => 'input-circle']) ?>
        </div>
    </div>
    <div class="row" id="fileUpload">
        <div class="col-md-12">
            <?= $form->field($model, 'fileUpload')->fileInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
        </div>
        <div class="col-md-offset-2 col-md-10 text-left">
            <?= Html::a(Yii::t('app',"Tải file mẫu"), $model->getTemplateFilePrice()) ?>
        </div>
        <?php if ($model->errorFile) { ?>
            <div class="row">
                <div class="col-md-offset-2 col-md-10">
                    <?= Html::a(Yii::t("app","Tải file chi tiết lỗi"), $model->errorFile) ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'mt_template_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\MtTemplate::find()->andWhere(['IN', 'mo_key', ['DK1', 'DK2', 'DK3']])->asArray()->all(), 'id', 'mo_key')) ?>
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

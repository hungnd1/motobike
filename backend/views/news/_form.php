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
            <?= $form->field($model, 'title')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'is_slide')->checkbox()->label('Hiển thị slide') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'fruit_id')->dropDownList(ArrayHelper::map(\common\models\Fruit::find()->asArray()->all(), 'id', 'name'),
                ['class' => 'form-control fruit', 'prompt' => ' -- Chọn cây trồng --',
                    'onchange' => '$.post("' . Url::toRoute('category/get-fruit') . '", 
                {id: $(this).val()}, 
                function(res){
                    $("#emeliyyatlar").html(res);
                });',

                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'category_id')
                ->dropDownList(ArrayHelper::map(\common\models\Category::find()->andWhere(['fruit_id'=>$model->fruit_id])->asArray()->all(), 'id', 'display_name'), ['class' => 'form-control category','id'=>'emeliyyatlar', 'prompt' => ' -- Chọn danh mục --']);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if ($showPreview) { ?>
                <div class="form-group field-category-icon">
                    <div class="col-sm-offset-3 col-sm-5">
                        <?php echo Html::img($model->getImageLink(), ['class' => 'file-preview-image']) ?>
                    </div>
                </div>
            <?php } ?>

            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options' => ['multiple' => true, 'accept' => 'image/*'],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'showUpload' => false,
                    'showPreview' => (!$showPreview) ? true : false,
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'description')->widget(\common\widgets\CKEditor::className(), [
                'options' => ['rows' => 8],
                'preset' => 'full'
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
                'preset' => 'full',
            ]);
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\News::listStatus(), ['class' => 'input-circle']
            ) ?>
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

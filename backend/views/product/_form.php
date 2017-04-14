<?php

use common\models\Shopbike;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php


$price_id = Html::getInputId($model, 'price');
$price_download = Html::getInputId($model, 'price_promotion');
$upload_update_url = \yii\helpers\Url::to(['/product/upload-file', 'id' => $model->id]);
$upload_create_url = \yii\helpers\Url::to(['/product/upload-file']);

$upload_url = $model->isNewRecord ? $upload_create_url : $upload_update_url;

$js = <<<JS
$(document).ready(function() {
    var the_terms = $("#free_id");

    if (the_terms.is(":checked")) {
        $("#pricing_id").attr("disabled", "disabled");
    } else {
        $("#pricing_id").removeAttr("disabled");
    }

    the_terms.click(function() {
        if ($(this).is(":checked")) {
            $("#pricing_id").attr("disabled", "disabled");
        } else {
            $("#pricing_id").removeAttr("disabled");
        }
    });
    // the_terms.click();

    $('button.kv-file-remove').click(function(e){
        console.log(e);
    });

});
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>


<div class="form-body">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'id' => 'form-create-content',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,

    ]); ?>

    <h3 class="form-section"><?= Yii::t('app', 'Thông tin sản phẩm') ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'display_name')->textInput(['maxlength' => 128, 'class' => 'form-control  input-circle']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'code')->textInput(['maxlength' => 128, 'class' => 'form-control  input-circle', 'readonly' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'price')->textInput(['maxlength' => 128, 'class' => 'form-control  input-circle']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'price_promotion')->textInput(['maxlength' => 128, 'class' => 'form-control  input-circle']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\Product::getListStatus('filter'), ['class' => 'input-circle']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'short_description')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'basic'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'options' => ['rows' => 8],
                'preset' => 'full'
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model,'shopbikeProductAsm')->widget(\kartik\select2\Select2::className(),[
                'data'=>\yii\helpers\ArrayHelper::map(Shopbike::find()->andWhere(['status'=>Shopbike::STATUS_ACTIVE])->asArray()->all(),'id','username'),
                'options' => ['placeholder' => 'Chọn hãng xe', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],

            ])->label('Chọn hãng xe');;
            ?>
        </div>
    </div>


    <h3 class="form-section"><?= Yii::t('app', 'Ảnh') ?> </h3>

    <div class="row">
        <div class="col-md-12">

            <?=
            $form->field($model, 'thumbnail[]')->widget(\kartik\widgets\FileInput::classname(), [
                'options' => [
                    'multiple' => false,
                    'id' => 'content-thumbnail',
                    'accept' => 'image/*'
                ],
                'pluginOptions' => [
                    'uploadUrl' => $upload_url,
                    'uploadExtraData' => [
                        'type' => \common\models\Product::IMAGE_TYPE_THUMBNAIL,
                        'thumbnail_old' => $model->thumbnail
                    ],
                    'language' => 'vi-VN',
                    'showUpload' => false,
                    'showUploadedThumbs' => false,
                    'initialPreview' => $thumbnailPreview,
                    'initialPreviewConfig' => $thumbnailInit,
                    'maxFileSize' => 1024 * 1024 * 10,
                ],
                'pluginEvents' => [
                    "fileuploaded" => "function(event, data, previewId, index) {
                    var response=data.response;
                    if(response.success){
                        var current_screenshots=response.output;
                        var old_value_text=$('#images_tmp').val();
                        if(old_value_text !=null && old_value_text !='' && old_value_text !=undefined)
                        {
                            var old_value=jQuery.parseJSON(old_value_text);

                            if(jQuery.isArray(old_value)){
                                old_value = old_value.filter(function(v){
                                    v = jQuery.parseJSON(v)
                                    console.log(typeof v.type, v.type);
                                    return v.type !== '2';
                                })
                                console.log(old_value);
                                old_value.push(current_screenshots);
                                console.log(old_value);
                            }
                        }
                        else{
                            var old_value= [current_screenshots];
                        }
                        $('#images_tmp').val(JSON.stringify(old_value));
                    }
                }",
                    "filedeleted" => "function(event, data) {
                    var response = data.response
                    console.log(event);
                    console.log(data);
                    // if(response.success){
                    //     console.log(response.output);

                    // }
                }",
                ],

            ]) ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'screenshoot[]')->widget(\kartik\widgets\FileInput::classname(), [
                'options' => [
                    'multiple' => true,
                    'accept' => 'image/png,image/jpg,image/jpeg,image/gif',
                    'id' => 'content-screenshoot'
                ],
                'pluginOptions' => [
                    'uploadUrl' => $upload_url,
                    'uploadExtraData' => [
                        'type' => \common\models\Product::IMAGE_TYPE_SCREENSHOOT,
                        'screenshots_old' => $model->screenshoot
                    ],
                    'maxFileCount' => 20,
                    'showUpload' => false,
                    'initialPreview' => $screenshootPreview,
                    'initialPreviewConfig' => $screenshootInit,
                    'maxFileSize' => 1024 * 1024 * 10,
                ],
                'pluginEvents' => [
                    "fileuploaded" => "function(event, data, previewId, index) {
                        var response=data.response;
                        if(response.success){
                            var current_screenshots=response.output;
                            var old_value_text=$('#images_tmp').val();
                            if(old_value_text !=null && old_value_text !='' && old_value_text !=undefined)
                            {
                                var old_value=jQuery.parseJSON(old_value_text);

                                if(jQuery.isArray(old_value)){
                                    old_value.push(current_screenshots);

                                }
                            }
                            else{
                                var old_value= [current_screenshots];
                            }
                            $('#images_tmp').val(JSON.stringify(old_value));
                         }
                     }",
                    "filesuccessremove" => "function() {  console.log('delete'); }",
                ],

            ]) ?>

        </div>
    </div>


    <div class="row">

        <div class="form-group field-content-price">
            <label class="control-label col-md-2" for="content-price"><?= Yii::t('app', 'Danh mục') ?></label>

            <div class="col-md-10">
                <?= \common\widgets\Jstree::widget([
                    'clientOptions' => [
                        "checkbox" => ["keep_selected_style" => false],
                        "plugins" => ["checkbox"]
                    ],
                    'type' => 1,
                    'cp_id' => true,
                    'data' => isset($selectedCats) ? $selectedCats : [],
                    'eventHandles' => [
                        'changed.jstree' => "function(e,data) {
                            jQuery('#list-cat-id').val('');
                            var i, j, r = [];
                            var catIds='';
                            for(i = 0, j = data.selected.length; i < j; i++) {
                                var item = $(\"#\" + data.selected[i]);
                                var value = item.attr(\"id\");
                                if(i==j-1){
                                    catIds += value;
                                } else{
                                    catIds += value +',';

                                }
                            }
                            jQuery(\"#default_category_id\").val(data.selected[0])
                            jQuery(\"#list-cat-id\").val(catIds);
                            console.log(jQuery(\"#list-cat-id\").val());
                         }"
                    ]
                ]) ?>
            </div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <!--    --><?php //endif; ?>
    <?= $form->field($model, 'list_cat_id')->hiddenInput(['id' => 'list-cat-id'])->label(false) ?>





    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'images')->hiddenInput(['id' => 'images_tmp'])->label(false) ?>
    <?php endif; ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo') : Yii::t('app', 'Cập nhật'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


    <?php ActiveForm::end(); ?>

</div>

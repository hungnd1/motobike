<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ImportDeviceForm */

$this->title = Yii::t('app', 'Import giá');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Danh sách thiết bị'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?=Yii::t("app","Import giá")?>
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
                    'id' => 'form-import-devices',
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    //'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                ]) ?>

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-10 text-right">
                            <?= Html::a(Yii::t('app',"Tải file mẫu"), $model->getTemplateFilePrice()) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'uploadedFile')->fileInput() ?>
                        </div>
                    </div>
                    <?php if ($model->errorFile) { ?>
                        <div class="row">
                            <div class="col-md-offset-2 col-md-10">
                                <?= Html::a(Yii::t("app","Tải file chi tiết lỗi"), $model->errorFile) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Import', ['class' => 'btn btn-primary',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Bạn chắc chắn muốn import giá không?'),
                                ]
                            ]) ?>
                            <?= Html::a(Yii::t("app","Quay lại"), ['index'], ['class' => 'btn btn-default']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>


<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app','Tạo sản phẩm');
$this->params['breadcrumbs'][] = ['label' => 'Sản phẩm', 'url' => Yii::$app->urlManager->createUrl(['product/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?= $this->title ?>
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                    'thumbnailInit' => $thumbnailInit,
                    'thumbnailPreview' => $thumbnailPreview,
                    'screenshootInit' => $screenshootInit,
                    'screenshootPreview' => $screenshootPreview,
                    'selectedCats' => $selectedCats,
                ]) ?>
            </div>
        </div>
    </div>
</div>

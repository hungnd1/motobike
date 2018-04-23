<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Feature */

$this->title = Yii::t('app','Cập nhật đặc điểm cây trồng');
$this->params['breadcrumbs'][] = ['label' => 'Đặc điểm cây trồng ', 'url' => Yii::$app->urlManager->createUrl(['/feature/index'])];
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
                ]) ?>
            </div>
        </div>
    </div>
</div>


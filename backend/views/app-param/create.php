<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AppParam */

$this->title = 'Tạo cấu hình';
$this->params['breadcrumbs'][] = ['label' => 'Cấu hình ', 'url' => Yii::$app->urlManager->createUrl(['/app-param/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo cấu hình
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

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UnitLink */

$this->title = 'Tạo đơn vị liên kết';
$this->params['breadcrumbs'][] = ['label' => 'Đơn vị liên kết ', 'url' => Yii::$app->urlManager->createUrl(['/unit-link/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo đơn vị liên kết
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

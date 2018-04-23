<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Fruit */

$this->title = 'Tạo loại cây trồng';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý loại cây trồng ', 'url' => Yii::$app->urlManager->createUrl(['/fruit/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo loại cây trồng
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

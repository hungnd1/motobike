<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Banner */

$this->title = 'Tạo banner';
$this->params['breadcrumbs'][] = ['label' => 'Banner ', 'url' => Yii::$app->urlManager->createUrl(['/banner/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo banner
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

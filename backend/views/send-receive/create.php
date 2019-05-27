<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = 'Gửi tin nhắn';
$this->params['breadcrumbs'][] = ['label' => 'Gửi tin nhắn', 'url' => Yii::$app->urlManager->createUrl(['/send-receive/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Gửi tin nhắn
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

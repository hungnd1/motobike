<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Group */

$this->title = 'Tạo nhóm cây trồng';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhóm cây trồng ', 'url' => Yii::$app->urlManager->createUrl(['/group/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo nhóm cây trồng
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

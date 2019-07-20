<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AppParam */

$this->title = Yii::t('app','Cập nhật nông dân');
$this->params['breadcrumbs'][] = ['label' => 'Cập nhật nông dân ', 'url' => Yii::$app->urlManager->createUrl(['/company-profile/index'])];
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


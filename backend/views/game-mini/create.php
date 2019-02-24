<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GameMini */

$this->title = 'Tạo tin tức';
$this->params['breadcrumbs'][] = ['label' => 'Game mini ', 'url' => Yii::$app->urlManager->createUrl(['/game-mini/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo câu hỏi
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

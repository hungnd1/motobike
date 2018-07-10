<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Fruit */

$this->title = 'Tạo câu hỏi phân bón';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý câu hỏi phân bón ', 'url' => Yii::$app->urlManager->createUrl(['/question/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo câu hỏi phân bón
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

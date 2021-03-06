<?php

/* @var $this yii\web\View */
/* @var $model common\models\MatrixFertilizing */

$this->title = '' . \Yii::t('app', 'Cập nhật ma trận phân bón');
$this->params['breadcrumbs'][] = ['label' => 'GAP ', 'url' => Yii::$app->urlManager->createUrl(['/matrix-fertilizing/index'])];
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
                    'listQuestion'=>$listQuestion,
                    'fruit_id' => $fruit_id
                ]) ?>
            </div>
        </div>
    </div>
</div>


<?php

use common\models\GapGeneral;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GapGeneral */

if($type == GapGeneral::GAP_GENERAL ){
    $this->title = '' . \Yii::t('app', 'Cập nhật tin sâu bệnh');
}else{
    $this->title = '' . \Yii::t('app', 'Cập nhật GAP chi tiết');
}
$this->params['breadcrumbs'][] = ['label' => 'GAP ', 'url' => Yii::$app->urlManager->createUrl(['/gap-general/index','type'=>$type])];
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
                    'type' => $type
                ]) ?>
            </div>
        </div>
    </div>
</div>


<?php

use common\models\GapGeneral;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Term */

$this->title = '' . \Yii::t('app', 'Tạo quy định');
$this->params['breadcrumbs'][] = ['label' => 'Quy định ', 'url' => Yii::$app->urlManager->createUrl(['/term/index'])];
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

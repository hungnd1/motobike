<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MtTemplate */

$this->title = 'Update Mt Template: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mt Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mt-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MoMt */

$this->title = 'Create Mo Mt';
$this->params['breadcrumbs'][] = ['label' => 'Mo Mts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mo-mt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

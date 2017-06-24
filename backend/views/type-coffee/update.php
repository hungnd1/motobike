<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TypeCoffee */

$this->title = 'Update Type Coffee: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Type Coffees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="type-coffee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

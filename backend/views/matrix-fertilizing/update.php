<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MatrixFertilizing */

$this->title = 'Update Matrix Fertilizing: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Matrix Fertilizings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="matrix-fertilizing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

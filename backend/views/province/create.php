<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Province */

$this->title = 'Tạo tỉnh';
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="province-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

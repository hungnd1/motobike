<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MtTemplate */

$this->title = 'Create Mt Template';
$this->params['breadcrumbs'][] = ['label' => 'Mt Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mt-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

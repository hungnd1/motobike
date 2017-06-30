<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ExchangeBuy */

$this->title = 'Create Exchange Buy';
$this->params['breadcrumbs'][] = ['label' => 'Exchange Buys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exchange-buy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

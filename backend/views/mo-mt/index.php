<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MoMtSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mo Mts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mo-mt-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mo Mt', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'from_number',
            'to_number',
            'message_mo:ntext',
            'request_id',
            // 'message_mt:ntext',
            // 'status_sync',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

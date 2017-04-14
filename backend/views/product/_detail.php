<?php
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Content;

/**
 * @var \common\models\Product $model
 */
?>
<?php
$grid = [
            [
                'attribute' => 'display_name',
            ],
            [
                'attribute' => 'code'
            ],
            'short_description:html',
            'description:html',
            [
                'attribute' => 'status',
                'format'=>'html',
                'value' =>"<span class='".$model->getCssStatus()."'>" . $model->getStatusName()."</span>"
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d-m-Y H:i:s', $model->created_at)
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d-m-Y H:i:s', $model->updated_at)
            ],
            [
                'label' => Yii::t('app','Ngày phê duyệt'),
                'value' => $model->approved_at?date('d-m-Y H:i:s', $model->approved_at):''
            ],
        ];


$grid = array_merge($grid, $model->viewAttr);

 ?>
<?= DetailView::widget([
    'model' => $model,
    'condensed' => true,
    'hover' => true,
    'mode' => DetailView::MODE_VIEW,
    'labelColOptions' => ['style' => 'width: 20%'],
    'attributes' => $grid
]) ?>

<?php

use common\models\Content;
use kartik\export\ExportMenu;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $report \backend\models\ReportSubscriberActivityForm */
/* @var $this yii\web\View */

$this->title = '' . \Yii::t('app', 'Báo cáo tổng doanh thu mua bán');
$this->params['breadcrumbs'][] = $this->title;

//$js = <<<JS
//    function onchangeTypeTime(){
//        var value =$('#typeTime').val();
//         if(value ==1){
//            $("#date").show();
//            $("#month").hide();
//        }else if(value ==2){
//            $("#date").hide();
//            $("#month").show();
//        }
//    }
//    $(document).ready(function () {
//        onchangeTypeTime();
//    });
//JS;
//$this->registerJs($js, \yii\web\View::POS_END);
//$this->registerJs('onchangeTypeTime()');
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">

                <div class="report-user-daily-index">

                    <div class="row form-group">
                        <div class="col-md-12 col-md-offset-0">
                            <?php $form = ActiveForm::begin(
                                ['method' => 'get',
                                    'action' => Url::to(['report/report-buy-sell']),]
                            ); ?>

                            <div class="row">

                                <div class="col-md-12">

                                    <div id="date">
                                        <div class="col-md-2">
                                            <?= $form->field($report, 'province_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Province::find()->asArray()->all(), 'id', 'province_name')) ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?= $form->field($report, 'type_coffee')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\TypeCoffee::find()->asArray()->all(), 'id', 'name')) ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?= $form->field($report, 'from_date')->widget(\kartik\widgets\DatePicker::classname(), [
                                                'options' => ['placeholder' => '' . \Yii::t('app', 'Ngày bắt đầu')],
                                                'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'todayHighlight' => true,
                                                    'format' => 'dd/mm/yyyy'
                                                ]
                                            ]); ?>

                                        </div>
                                        <div class="col-md-2">
                                            <?= $form->field($report, 'to_date')->widget(\kartik\widgets\DatePicker::classname(), [
                                                'options' => ['placeholder' => '' . \Yii::t('app', 'Ngày kết thúc')],
                                                'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'todayHighlight' => true,
                                                    'format' => 'dd/mm/yyyy'
                                                ]
                                            ]); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div style="margin-top: 25px"></div>
                                        <?= \yii\helpers\Html::submitButton('' . \Yii::t('app', 'Xem báo cáo'), ['class' => 'btn btn-primary']) ?>
                                    </div>

                                </div>
                            </div>


                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                    <?php if ($dataProvider) { ?>
                        <?php
                        $gridColumns = [
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'report_date',
                                'width' => '150px',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\ReportBuySell */
                                    return !empty($model->report_date) ? date('d-m-Y', $model->report_date) : '';
                                },
                                'pageSummary' => "" . \Yii::t('app', 'Thời gian')
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'province_id',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\ReportBuySell */
                                    return \common\models\Province::findOne($model->province_id)->province_name;
                                },
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'type_coffee',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\ReportBuySell */
                                    return \common\models\TypeCoffee::findOne($model->type_coffee)->name;
                                },
                                'pageSummary' => true,
//                                                        'pageSummary' => $dataProvider->query->sum('via_site_daily')?$dataProvider->query->sum('via_site_daily'):0
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'total_buy',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\ReportBuySell */
                                    return $model->total_buy;
                                },
                                'pageSummary' => true,
//                                                        'pageSummary' => $dataProvider->query->sum('via_android')?$dataProvider->query->sum('via_android'):0
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'total_sell',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\ReportBuySell */
                                    return $model->total_sell;
                                },
                                'pageSummary' => true,
//                                                        'pageSummary' => $dataProvider->query->sum('via_android')?$dataProvider->query->sum('via_android'):0
                            ],
                        ]
                        ?>

                        <?php
                        $expMenu = ExportMenu::widget([
                            'dataProvider' => $excelDataProvider,
                            //'columns' => $gridColumns,
                            'showConfirmAlert' => false,
                            'fontAwesome' => true,
                            'showColumnSelector' => true,
                            'dropdownOptions' => [
                                'label' => '' . \Yii::t('app', 'All'),
                                'class' => 'btn btn-default'
                            ],
                            'exportConfig' => [
                                ExportMenu::FORMAT_CSV => false,
                                ExportMenu::FORMAT_EXCEL_X => [
                                    'label' => 'Excel',
                                ],
                                ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_PDF => false,
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_EXCEL => false,
                            ],
                            'target' => ExportMenu::TARGET_SELF,
                            'filename' => "Report"
                        ])

                        ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'responsive' => true,
                            'pjax' => true,
                            'hover' => true,
                            'showPageSummary' => true,
                            'columns' => $gridColumns,
                            'panel' => [
                                'type' => GridView::TYPE_DEFAULT,
                            ],
                            'toolbar' => [
                                '{export}',
                                $expMenu,
                                ['content' =>
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['subscriber-activity'], [
                                        'data-pjax' => 0,
                                        'class' => 'btn btn-default',
                                        'title' => Yii::t('kvgrid', 'Reset Grid')
                                    ])
                                ],
                            ],
                            'export' => [
                                'label' => "Page",
                                'fontAwesome' => true,
                                'showConfirmAlert' => false,
                                'target' => GridView::TARGET_BLANK,

                            ],
                            'exportConfig' => [
                                GridView::EXCEL => ['label' => 'Excel', 'filename' => "Report"],
                            ],
                        ]); ?>
                    <?php } else { ?>
                        <div class="portlet-body">
                            <div class="well well-sm">
                                <p><?= \Yii::t('app', 'Không có dữ liệu') ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
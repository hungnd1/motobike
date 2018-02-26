<?php

use backend\models\ReportSubscriberAction;
use common\models\Content;
use common\models\Subscriber;
use kartik\export\ExportMenu;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $report \backend\models\ReportSubscriberActivityForm */
/* @var $this yii\web\View */

$this->title = '' . \Yii::t('app', 'Báo cáo hành vi người dùng');
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
                                    'action' => Url::to(['report/subscriber-action']),]
                            ); ?>

                            <div class="row">

                                <div class="col-md-12">

                                    <div id="date">
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
                                'attribute' => 'msisdn',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\SubscriberActivity */
                                    return $model->msisdn;
                                },
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'label' => 'Tên',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\SubscriberActivity */
                                    $subscriber = Subscriber::findOne($model->subscriber_id);
                                    if($subscriber){
                                        return $subscriber->full_name;
                                    }
                                    return $model->msisdn;
                                },
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'action',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\SubscriberActivity */
                                    return $model->getActionName();
                                },
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'created_at',
                                'width' => '150px',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\SubscriberActivity */
                                    return !empty($model->created_at) ? date('d-m-Y', $model->created_at) : '';
                                },
                                'pageSummary' => "" . \Yii::t('app', 'Tổng số')
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'channel',
                                'value' => function ($model) {
                                    /**  @var $model \common\models\SubscriberActivity */
                                    return $model->getChannelName();
                                },
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
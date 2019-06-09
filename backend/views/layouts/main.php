<?php

use common\models\AuthItem;
use common\models\Category;
use common\models\GapGeneral;
use common\models\User;
use common\widgets\Alert;
use common\widgets\Nav2;
use sp\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$this->registerJs("Metronic.init();");
$this->registerJs("Layout.init();");
$arrlang = array();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid">
<?php $this->beginBody() ?>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top" style="height: 70px">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">

        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?= Yii::$app->homeUrl ?>">
                <img style="height: 65px;width: 50%;margin: 1px 25px 7px 15px;"
                     src="<?= Url::to("@web/img/ic_launcher_1.png") ?>" alt="logo"
                     class="logo-default"/>
            </a>

            <div class="menu-toggler sidebar-toggler hide">
            </div>
        </div>

        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
        </a>

        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->


                <li class="dropdown dropdown-user">
                    <?php
                    if (Yii::$app->user->isGuest) {

                    } else {
                    ?>
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        <img alt="" class="img-circle" src="<?= Url::to("@web/img/avatar2.jpg") ?>"/>
                        <span class="username username-hide-on-mobile">
					<?= Yii::$app->user->identity->username ?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?= Url::to(['user/info']) ?>">
                                <i class="icon-user"></i> <?= Yii::t("app", "Thông tin tài khoàn") ?> </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['site/logout']) ?>" data-method='post'>
                                <i class="icon-logout"></i> <?= Yii::t("app", "Đăng xuất") ?> </a>
                        </li>
                    </ul>
                </li>
                <?php
                }
                ?>

                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="<?= Url::to(['site/logout']) ?>" class="dropdown-toggle" data-method='post'>
                        <i class="icon-logout"></i>
                    </a>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse" style="padding-top: 25px;">
            <?php


            $rightItems = [
                [
                    'label' => '<i class="fa fa-dashboard"></i> ' . \Yii::t('app', 'Bảng điều khiển'),
                    'url' => ['site/index'],
                    'encode' => false,
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Hệ thống'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'QL người dùng'),
                            'url' => ['user/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý thiết bị'),
                            'url' => ['device-info/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'QL Key'),
                            'url' => ['credential/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'Lịch sử tương tác'),
                            'url' => ['user-activity/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'QL phân  quyền'),
                            'url' => ['rbac-backend/permission'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'QL nhóm  quyền'),
                            'url' => ['rbac-backend/role'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý version App'),
                            'url' => ['version/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý banner web'),
                            'url' => ['banner/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý cấu hình'),
                            'url' => ['app-param/index'],
                            'require_auth' => true,
                        ],
                    ]
                ],
//                [
//                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> '.\Yii::t('app', 'Quản lý thông tin'),
//                    'url' => 'javascript:;',
//                    'encode' => false,
////                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
////                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
//                    'items' => [
//                        [
//                            'encode' => false,
//                            'label' => '<i class="icon-users"></i> '.\Yii::t('app', 'Quản lý tỉnh'),
//                            'url' => ['province/index'],
//                            'require_auth' => true,
//                        ],
//                    ]
//
//                ],
//                [
//                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> '.\Yii::t('app', 'Quản lý giao dịch'),
//                    'url' => 'javascript:;',
//                    'encode' => false,
////                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
////                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
//                    'items' => [
//                        [
//                            'encode' => false,
//                            'label' => '<i class="icon-users"></i> '.\Yii::t('app', 'Quản lý tổng sản lượng'),
//                            'url' => ['total-quality/index'],
//                            'require_auth' => true,
//                        ],
//                        [
//                            'encode' => false,
//                            'label' => '<i class="icon-users"></i> '.\Yii::t('app', 'Quản lý sản lượng đã bán'),
//                            'url' => ['sold/index'],
//                            'require_auth' => true,
//                        ],
//                        [
//                            'encode' => false,
//                            'label' => '<i class=" icon-eyeglasses"></i> '.\Yii::t('app', 'Quản lý loại coffee'),
//                            'url' => ['type-coffee/index'],
//                            'require_auth' => true,
//                        ],
//                    ]
//
//                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý FID'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý cây trồng'),
                            'url' => ['fruit/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý nhóm cây trồng'),
                            'url' => ['group/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý đặc điểm cây'),
                            'url' => ['feature/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class=" icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý thông tin chi tiết'),
                            'url' => ['detail/index'],
                            'require_auth' => true,
                        ],
                    ]

                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý tài khoản'),
                    'url' => ['subscriber/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý gói cước'),
                    'url' => ['service/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý giá'),
                    'url' => ['price-coffee/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý hỏi đáp'),
                    'url' => ['question-answer/index'],
                    'require_auth' => true,
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý hỏi đáp'),
                    'url' => 'javascript:;',
                    'encode' => false,
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý danh mục hỏi đáp'),
                            'url' => ['category/index', 'type' => \common\models\Category::TYPE_QA],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý câu hỏi'),
                            'url' => ['question-answer/index'],
                            'require_auth' => true,
                        ],
                    ]

                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý GAP chung'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý danh mục GAP chung'),
                            'url' => ['category/index', 'type' => \common\models\Category::TYPE_GAP_GOOD],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý GAP chung'),
                            'url' => ['news/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý GAP chi tiết'),
                            'url' => ['gap-general/index', 'type' => GapGeneral::GAP_DETAIL],
                            'require_auth' => true,
                        ],
                    ]

                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý sâu bệnh và phân bón'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý tin sâu bệnh, tin tức, sự kiện'),
                            'url' => ['gap-general/index', 'type' => GapGeneral::GAP_GENERAL],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý câu hỏi phân bón'),
                            'url' => ['question/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý tư vấn phân bón'),
                            'url' => ['matrix-fertilizing/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý biến đổi khỉ hậu'),
                            'url' => ['gap-general/index', 'type' => GapGeneral::CLIMATE_CHANGE],
                            'require_auth' => true,
                        ],
                    ]

                ],
//                [
//                    'encode' => false,
//                    'label' => '<i class="icon-eyeglasses"></i> '.\Yii::t('app', 'Quản lý thông tin AKVO'),
//                    'url' => ['log-data/index'],
//                    'require_auth' => true,
//                ],
//                [
//                    'encode' => false,
//                    'label' => '<i class="icon-eyeglasses"></i> '.\Yii::t('app', 'Quản lý quy định'),
//                    'url' => ['term/index'],
//                    'require_auth' => true,
//                ],
//                [
//                    'encode' => false,
//                    'label' => '<i class="icon-eyeglasses"></i> '.\Yii::t('app', 'Quản lý đơn vị liên kết'),
//                    'url' => ['unit-link/index'],
//                    'require_auth' => true,
//                ],
//                [
//                    'encode' => false,
//                    'label' => '<i class="icon-eyeglasses"></i> '.\Yii::t('app', 'Upload giá'),
//                    'url' => ['price-coffee/index'],
//                    'require_auth' => true,
//                ],
//                [
//                    'encode' => false,
//                    'label' => '<i class="icon-eyeglasses"></i> '.\Yii::t('app', 'Upload thời tiết'),
//                    'url' => ['weather-detail/index'],
//                    'require_auth' => true,
//                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý báo cáo'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Báo cáo lượng truy cập'),
                            'url' => ['report/subscriber-activity'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Báo cáo lượng thuê bao'),
                            'url' => ['report/subscriber-number'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Báo cáo hành vi người dùng'),
                            'url' => ['report/subscriber-action'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Báo cáo tổng doanh thu mua bán'),
                            'url' => ['report/report-buy-sell'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Báo cáo truy cập các chức năng'),
                            'url' => ['report/subscriber-activity-type'],
                            'require_auth' => true,
                        ],

                    ]

                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Chiến dịch'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý danh mục game'),
                            'url' => ['category/index', 'type' => Category::TYPE_GAME],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý câu hỏi game'),
                            'url' => ['game-mini/index'],
                            'require_auth' => true,
                        ],

                    ]

                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý MO MT'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý MO MT'),
                            'url' => ['mt-template/index'],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Gửi tin nhắn'),
                            'url' => ['send-receive/index'],
                            'require_auth' => true,
                        ],

                    ]
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ' . \Yii::t('app', 'Quản lý RA Certification'),
                    'url' => 'javascript:;',
                    'encode' => false,
//                    'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//                    'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                    'items' => [
                        [
                            'encode' => false,
                            'label' => '<i class="icon-eyeglasses"></i> ' . \Yii::t('app', 'Quản lý danh mục RA'),
                            'url' => ['category/index', 'type' => \common\models\Category::TYPE_RA],
                            'require_auth' => true,
                        ],
                        [
                            'encode' => false,
                            'label' => '<i class="icon-users"></i> ' . \Yii::t('app', 'Quản lý danh sách file RA'),
                            'url' => ['file-manage/index'],
                            'require_auth' => true,
                        ],

                    ]
                ],
            ];


            echo Nav2::widget([
                'options' => ['class' => "page-sidebar-menu  page-sidebar-fixed", 'data-keep-expanded' => "false", 'data-auto-scroll' => "true", 'data-slide-speed' => "200"],
                'items' => $rightItems,
                'activateParents' => true,
            ]);

            ?>
        </div>
    </div>
    <!-- END SIDEBAR -->


    <!-- BEGIN CONTAINER -->
    <div class="page-content-wrapper">
        <!--    <div class="page-head">-->
        <!--        <div class="container-fluid">-->
        <!--            <div class="page-title">-->
        <!--                <h1>--><?php //echo $this->title ?><!--</h1>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <div class="page-content">

            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'page-breadcrumb breadcrumb'
                ],
//                'itemTemplate' => "<li>{link}<i class=\"fa fa-circle\"></i></li>\n",
                'activeItemTemplate' => "<li class=\"active\">{link}</li>\n"
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>

        </div>
    </div>
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer footer">
    <div class="container-fluid">
        <p>
            <b>&copy;<?= Yii::t("app", "Copyright") ?>  <?php echo date('Y'); ?> </b><?= Yii::t("app", ". All Rights Reserved.") ?>
            <b><?= Yii::t("app", "CMS Multi Streamming Platform") ?></b>.
            <?= Yii::t("app", "Design By VIVAS Co.,Ltd.") ?></p>
    </div>
</div>
<div class="scroll-to-top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

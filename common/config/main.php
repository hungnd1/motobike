<?php


$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'vi', /**  set target language to be vi */
    'aliases' => [
        '@news_image' => 'static/news',
        '@avatar_image' => 'static/avatar',
        '@messages' => 'messages',
        '@question' => 'static/question',
        '@excel_folder' => "uploaded_excels",
        '@weather'=>'weather',
        '@unit_link'=>'static/unit_link',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\ApcCache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'frontend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'api*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
//                'zii*' => [
//                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@common/messages',
////                    'forceTranslation' => true
//                ],
//                'kvgrid*' => [
                //                    'class' => 'yii\i18n\PhpMessageSource',
                //                    'basePath' => '@common/messages',
                ////                    'forceTranslation' => true
                //                ],
            ],
        ],
    ],
    'timeZone' => 'Asia/Ho_Chi_Minh',
];


if (YII_ENV == 'prod' && extension_loaded('apcu')) {
    $config['components']['cache'] = [
        'class' => 'yii\caching\ApcCache',
        'useApcu' => true,
    ];
}


return $config;

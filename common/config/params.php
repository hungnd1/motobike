<?php
return [
    'adminEmail' => 'admin@example.com',
    'semantic_url' => 'http://semantic.tvod.vn/api/film',
    'semantic_url2' => 'http://se.tvod.vn',
    'semantic_url_new' => 'http://103.31.126.219/' ,
    'semantic_url_search_engine' => 'http://10.84.82.139/' ,
    'site_id' => 1,
    'secret_key' => "VNPT-Technology", //secret_key dùng để valid khi checkMac
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    // thanh toan the nap va the tvod
    'voucher_tvod_link' => 'http://10.84.82.240:8080',
    'voucher_phone_link' => 'http://cardcharging.vivas.vn:8000',
    'user_voucher' => 'DT',
    'pass_voucher' => '123@123',
    'mpin_voucher' => 'ssx5eruy',

    'sms_proxy' => [
        'url' => 'http://10.84.73.6:13013/cgi-bin/sendsms',
        'username' => 'tester',
        'password' => 'foobar',
        'debug' => false
    ],
    'access_private' => [
        'user_name' => 'msp_private',
        'password' => 'Msp!@123',
        'ip_privates' => [
            '192.0.0.0/8',
            '10.0.0.0/8',
            '10.84.0.0/16',
            '127.0.0.0/16',
        ],
    ],
    'tvod1Only' => false,
    'payment_gate' => [
        'active' => false,
        'url' => 'https://pay.smartgate.vn/Checkout',
        'tvod2_api_base_url' => 'http://103.31.126.223/api/web/index.php/',
        'merchant_id' => 'tvod',
        'secret_key' => 'uk3h3f',
        'command' => 'PAY',
        'order_type_digital' => 2,
    ],
    'sms_charging' => [
        'username' => 'tvod2',
        'password' => '123456',
    ],
    'auto_renew' => [
        'max_time_in_hours' => 0, // thoi gian quet gia han truoc khi het han
    ],
    'retry' => 3,
    'delay' => 1,
    'recommend_url' => 'http://10.84.82.11:5432/',
    'partner'=>[
        'voucher_tvod'=>[
            'key'=>'rdvias@123',
        ],
    ],
    'GreenCoffee' => 'https://greencoffee.lizard.net/api/v2/timeseries/?location__organisation__name=G4AW%20Green%20Coffee&format=json',
    'username' =>'duc.dame',
    'password'=>'Kopainfo2017',
    'price_detail'=>'https://greencoffee.lizard.net/api/v2/timeseries/?end=1690171661710&min_points=320&start=1488521600001&format=json&uuid=',
    'timeExpired'=> 7
];

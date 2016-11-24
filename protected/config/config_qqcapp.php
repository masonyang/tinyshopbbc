<?php return array (
    'classes' =>
        array (
            0 => 'classes.*',
            1 => 'extensions.*',
            2 => 'classes.barcode.*',
            3 => 'classes.payments.*',
            4 => 'classes.delivery.*',
            5 => 'classes.oauth.*',
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'sms_key'=>'23544559',//正式站
    'sms_secret'=>'3760897087f10c86ccb6c5952fdb39a6',
    'sms_reg_template'=>'SMS_29145046',//注册验证码
    'sms_resetpwd_template'=>'SMS_29205006',//重置密码
    'urlFormat' => 'get',
    'db' =>
        array (
            'type' => 'mysql',
            'tablePre' => 'tiny_',
            'host' => '10.45.54.89:3306',
            'user' => 'qqc',
            'password' => 'qqc-2016-mysql',
            'name' => 'tinyshop',
        ),
    'headStore'=>'zd',
    'route' =>
        array (
        ),
    'extConfig' =>
        array (
            'controllerExtension' =>
                array (
                    0 => 'ControllerExt',
                ),
        ),
);?>


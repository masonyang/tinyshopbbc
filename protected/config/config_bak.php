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
    'sms_key'=>'23352957',//测试用
    'sms_secret'=>'0eabb49be76012fcfb54d920a3cca9ab',
    'sms_reg_template'=>'SMS_25715133',//注册验证码
    'sms_resetpwd_template'=>'SMS_25815032',//重置密码
    'sms_clientsign'=>'手机app',//验证码短信签名
    'urlFormat' => 'get',
    'db' =>
        array (
            'type' => 'mysql',
            'tablePre' => 'tiny_',
            'host' => 'localhost:3306',
            'user' => 'root',
            'password' => '123456',
            'name' => 'tinyshop',
        ),
    'cache'=>
        array(
            'type'=>'memcache',
            'host'=>'127.0.0.1',
            'port'=>'11211',
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
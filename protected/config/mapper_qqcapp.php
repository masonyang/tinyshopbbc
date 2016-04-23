<?php
//总店、分店配置映射表
return array(
    'zd'=>array(
        'db'=>array(
            'type' => 'mysql',
            'domain'=>'zd',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'tinyshop',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'tinyshop',
            ),
        ),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
        'menu'=>'headstore',
        'frontend'=>'zd.qqcapp.com/index.php?con=admin&act=index',
        'backend'=>'zd.qqcapp.com/index.php?con=admin&act=index',
    ),
    'a'=>array(
        'db'=>array(
            'type' => 'mysql',
            'domain'=>'a',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'a',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'a',
            ),
        ),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
        'menu'=>'branchstore',
        'frontend'=>'a.qqcapp.com',
        'backend'=>'a.qqcapp.com/index.php?con=admin&act=index',
    ),
    'b' =>
        array (
            'db' =>
                array (
                    'type' => 'mysql',
                    'domain' => 'b',
                    'tablePre' => 'tiny_',
                    'master'=>array(
                        'host' => '10.45.54.89:3306',
                        'user' => 'qqc',
                        'password' => 'qqc-2016-mysql',
                        'name' => 'a',
                    ),
                    'salve'=>array(
                        'host' => '10.45.54.89:3306',
                        'user' => 'qqc',
                        'password' => 'qqc-2016-mysql',
                        'name' => 'a',
                    ),
                ),
            'theme' => 'default',
            'themes_mobile' => 'mobile_default',
            'menu' => 'branchstore',
            'frontend' => 'b.qqcapp.com',
            'backend' => 'b.qqcapp.com/index.php?con=admin&act=index',
        ),
    'c' =>
        array (
            'db' =>
                array (
                    'type' => 'mysql',
                    'domain' => 'c',
                    'tablePre' => 'tiny_',
                    'master'=>array(
                        'host' => '10.45.54.89:3306',
                        'user' => 'qqc',
                        'password' => 'qqc-2016-mysql',
                        'name' => 'c',
                    ),
                    'salve'=>array(
                        'host' => '10.45.54.89:3306',
                        'user' => 'qqc',
                        'password' => 'qqc-2016-mysql',
                        'name' => 'c',
                    ),
                ),
            'theme' => 'default',
            'themes_mobile' => 'mobile_default',
            'menu' => 'branchstore',
            'frontend' => 'c.qqcapp.com',
            'backend' => 'c.qqcapp.com/index.php?con=admin&act=index',
        ),
);

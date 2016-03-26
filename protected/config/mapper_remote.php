<?php
//总店、分店配置映射表
return array(
	'zd'=>array(
		'db'=>array(
            'type' => 'mysql',
            'domain'=>'zd',
            'tablePre' => 'tiny_',
			'master'=>array(
				'host' => 'localhost:3306',
				'user' => 'root',
				'password' => 'Zhangweilong',
				'name' => 'tinyshop',
			),
			'salve'=>array(
				'host' => 'localhost:3306',
				'user' => 'root',
				'password' => 'Zhangweilong',
				'name' => 'tinyshop',
			),
		),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
		'menu'=>'headstore',
		'frontend'=>'zd.nst1688.com/index.php?con=admin&act=index',
		'backend'=>'zd.nst1688.com/index.php?con=admin&act=index',
	),
	'a'=>array(
		'db'=>array(
            'type' => 'mysql',
            'domain'=>'a',
            'tablePre' => 'tiny_',
			'master'=>array(
				'host' => 'localhost:3306',
				'user' => 'root',
				'password' => 'Zhangweilong',
				'name' => 'a',
			),
			'salve'=>array(
				'host' => 'localhost:3306',
				'user' => 'root',
				'password' => 'Zhangweilong',
				'name' => 'a',
			),
		),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
		'menu'=>'branchstore',
		'frontend'=>'a.nst1688.com',
		'backend'=>'a.nst1688.com/index.php?con=admin&act=index',
	),
    '1'=>array(
        'db'=>array(
            'type' => 'mysql',
            'domain'=>'1',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => 'localhost:3306',
                'user' => 'root',
                'password' => 'Zhangweilong',
                'name' => '1',
            ),
            'salve'=>array(
                'host' => 'localhost:3306',
                'user' => 'root',
                'password' => 'Zhangweilong',
                'name' => '1',
            ),
        ),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
        'menu'=>'branchstore',
        'frontend'=>'1.nst1688.com',
        'backend'=>'1.nst1688.com/index.php?con=admin&act=index',
    ),
    'b'=>array(
        'db'=>array(
            'type' => 'mysql',
            'domain'=>'b',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => 'localhost:3306',
                'user' => 'root',
                'password' => 'Zhangweilong',
                'name' => 'b',
            ),
            'salve'=>array(
                'host' => 'localhost:3306',
                'user' => 'root',
                'password' => 'Zhangweilong',
                'name' => 'b',
            ),
        ),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
        'menu'=>'branchstore',
        'frontend'=>'b.nst1688.com',
        'backend'=>'b.nst1688.com/index.php?con=admin&act=index',
    ),
    'c'=>array(
        'db'=>array(
            'type' => 'mysql',
            'domain'=>'c',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => 'localhost:3306',
                'user' => 'root',
                'password' => 'Zhangweilong',
                'name' => 'c',
            ),
            'salve'=>array(
                'host' => 'localhost:3306',
                'user' => 'root',
                'password' => 'Zhangweilong',
                'name' => 'c',
            ),
        ),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
        'menu'=>'branchstore',
        'frontend'=>'c.nst1688.com',
        'backend'=>'c.nst1688.com/index.php?con=admin&act=index',
    ),
);

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
				'user' => 'qqc',
				'password' => 'qqc',
				'name' => 'qqc_zd',
			),
			'salve'=>array(
				'host' => 'localhost:3306',
				'user' => 'qqc',
				'password' => 'qqc',
				'name' => 'qqc_zd',
			),
		),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
		'menu'=>'headstore',
		'frontend'=>'zd.git-online.com/index.php?con=admin&act=index',
		'backend'=>'zd.git-online.com/index.php?con=admin&act=index',
	),
	'a'=>array(
		'db'=>array(
            'type' => 'mysql',
            'domain'=>'a',
            'tablePre' => 'tiny_',
			'master'=>array(
				'host' => 'localhost:3306',
				'user' => 'qqc',
				'password' => 'qqc',
				'name' => 'qqc_a',
			),
			'salve'=>array(
				'host' => 'localhost:3306',
				'user' => 'qqc',
				'password' => 'qqc',
				'name' => 'qqc_a',
			),
		),
        'theme' => 'default',
        'themes_mobile' => 'mobile_default',
		'menu'=>'branchstore',
		'frontend'=>'a.git-online.com',
		'backend'=>'a.git-online.com/index.php?con=admin&act=index',
	),
);

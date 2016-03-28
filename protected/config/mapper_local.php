<?php return array (
  'zd' => 
  array (
    'db' => 
    array (
      'type' => 'mysql',
      'domain' => 'zd',
      'tablePre' => 'tiny_',
      'master' => 
      array (
        'host' => 'localhost:3306',
        'user' => 'root',
        'password' => '123456',
        'name' => 'tinyshop',
      ),
      'salve' => 
      array (
        'host' => 'localhost:3306',
        'user' => 'root',
        'password' => '123456',
        'name' => 'tinyshop',
      ),
    ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'headstore',
    'frontend' => 'zd.tinyshop.com/index.php?con=admin&act=index',
    'backend' => 'zd.tinyshop.com/index.php?con=admin&act=index',
  ),
  'a' => 
  array (
    'db' => 
    array (
      'type' => 'mysql',
      'domain' => 'a',
      'tablePre' => 'tiny_',
      'master' => 
      array (
        'host' => 'localhost:3306',
        'user' => 'root',
        'password' => '123456',
        'name' => 'a',
      ),
      'salve' => 
      array (
        'host' => 'localhost:3306',
        'user' => 'root',
        'password' => '123456',
        'name' => 'a',
      ),
    ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'a.tinyshop.com',
    'backend' => 'a.tinyshop.com/index.php?con=admin&act=index',
  ),
);
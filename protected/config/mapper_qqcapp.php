<?php
//总店、分店配置映射表
$db = array(
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
                        'name' => 'b',
                    ),
                    'salve'=>array(
                        'host' => '10.45.54.89:3306',
                        'user' => 'qqc',
                        'password' => 'qqc-2016-mysql',
                        'name' => 'b',
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

$db['d'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'd',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'd',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'd',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'd.qqcapp.com',
    'backend' => 'd.qqcapp.com/index.php?con=admin&act=index',
);
$db['e'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'e',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'e',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'e',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'e.qqcapp.com',
    'backend' => 'e.qqcapp.com/index.php?con=admin&act=index',
);
$db['f'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'f',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'f',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'f',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'f.qqcapp.com',
    'backend' => 'f.qqcapp.com/index.php?con=admin&act=index',
);
$db['g'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'g',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'g',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'g',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'g.qqcapp.com',
    'backend' => 'g.qqcapp.com/index.php?con=admin&act=index',
);
$db['h'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'h',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'h',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'h',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'h.qqcapp.com',
    'backend' => 'h.qqcapp.com/index.php?con=admin&act=index',
);
$db['i'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'i',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'i',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'i',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'i.qqcapp.com',
    'backend' => 'i.qqcapp.com/index.php?con=admin&act=index',
);
$db['j'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'j',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'j',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'j',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'j.qqcapp.com',
    'backend' => 'j.qqcapp.com/index.php?con=admin&act=index',
);
$db['k'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'k',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'k',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'k',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'k.qqcapp.com',
    'backend' => 'k.qqcapp.com/index.php?con=admin&act=index',
);
$db['l'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'l',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'l',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'l',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'l.qqcapp.com',
    'backend' => 'l.qqcapp.com/index.php?con=admin&act=index',
);
$db['n'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'n',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'n',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'n',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'n.qqcapp.com',
    'backend' => 'n.qqcapp.com/index.php?con=admin&act=index',
);
$db['o'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'o',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'o',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'o',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'o.qqcapp.com',
    'backend' => 'o.qqcapp.com/index.php?con=admin&act=index',
);
$db['p'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'p',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'p',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'p',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'p.qqcapp.com',
    'backend' => 'p.qqcapp.com/index.php?con=admin&act=index',
);
$db['q'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'q',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'q',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'q',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'q.qqcapp.com',
    'backend' => 'q.qqcapp.com/index.php?con=admin&act=index',
);
$db['r'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'r',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'r',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'r',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'r.qqcapp.com',
    'backend' => 'r.qqcapp.com/index.php?con=admin&act=index',
);
$db['s'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 's',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 's',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 's',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 's.qqcapp.com',
    'backend' => 's.qqcapp.com/index.php?con=admin&act=index',
);
$db['t'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 't',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 't',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 't',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 't.qqcapp.com',
    'backend' => 't.qqcapp.com/index.php?con=admin&act=index',
);
$db['u'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'u',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'u',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'u',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'u.qqcapp.com',
    'backend' => 'u.qqcapp.com/index.php?con=admin&act=index',
);
$db['v'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'v',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'v',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'v',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'v.qqcapp.com',
    'backend' => 'v.qqcapp.com/index.php?con=admin&act=index',
);
$db['w'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'w',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'w',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'w',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'w.qqcapp.com',
    'backend' => 'w.qqcapp.com/index.php?con=admin&act=index',
);
$db['x'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'x',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'x',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'x',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'x.qqcapp.com',
    'backend' => 'x.qqcapp.com/index.php?con=admin&act=index',
);
$db['y'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'y',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'y',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'y',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'y.qqcapp.com',
    'backend' => 'y.qqcapp.com/index.php?con=admin&act=index',
);
$db['z'] = array (
    'db' =>
        array (
            'type' => 'mysql',
            'domain' => 'z',
            'tablePre' => 'tiny_',
            'master'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'z',
            ),
            'salve'=>array(
                'host' => '10.45.54.89:3306',
                'user' => 'qqc',
                'password' => 'qqc-2016-mysql',
                'name' => 'z',
            ),
        ),
    'theme' => 'default',
    'themes_mobile' => 'mobile_default',
    'menu' => 'branchstore',
    'frontend' => 'z.qqcapp.com',
    'backend' => 'z.qqcapp.com/index.php?con=admin&act=index',
);

require('mapper_qqcapp_aatoaz.php');

return $db;


<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/4/17
 * Time: 下午10:36
 *
 * 升级执行sql脚本代码 H5 app项目
 *
 */

//插入 分店 数据表数据
$upgradedata['branch']['insert'] = array(
    'pay_plugin'=>array(
        'where'=>'name="微信[公众号支付]"',
        'sql'=>"INSERT INTO `tiny_pay_plugin` (`name`, `class_name`, `description`, `logo`) VALUES ('微信[公众号支付]', 'wechat', '微信公众号支付', '/payments/logos/pay_wechat.gif');",
    ),
);

//更新 分店 数据表数据
$upgradedata['branch']['update'] = array();

//删除 分店 数据表数据
$upgradedata['branch']['delete'] = array();


//插入 总店 数据表数据
$upgradedata['head']['insert'] = array(
    'pay_plugin'=>array(
        'where'=>'name="微信[公众号支付]"',
        'sql'=>"INSERT INTO `tiny_pay_plugin` (`name`, `class_name`, `description`, `logo`) VALUES ('微信[公众号支付]', 'wechat', '微信公众号支付', '/payments/logos/pay_wechat.gif');",
    ),
);

//更新 总店 数据表数据
$upgradedata['head']['update'] = array();

//删除 总店 数据表数据
$upgradedata['head']['delete'] = array();






//创建 分店 数据表结构
$upgradeschema['branch']['create'] = array(
    'goods_sales_statistics'=>"CREATE TABLE `tiny_goods_sales_statistics` (
 `stat_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `gid` bigint(20) NOT NULL COMMENT '商品id',
  `gcount` int(11) DEFAULT NULL COMMENT '商品销售数量',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`stat_id`),
  KEY idx_gcount (gcount)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品销量统计';",
);

//更新 分店 数据表结构
$upgradeschema['branch']['modify'] = array(
    'user'=>array(
        'wxOpenId'=>"alter table `tiny_user` add columns `wxOpenId` varchar(100) comment '微信用户openId';",
        'wxAccessToken'=>"alter table `tiny_user` add column `wxAccessToken` varchar(200) comment '微信用户授权access_token';",
    ),
    'order'=>array(
        'order_refer'=>"alter table `tiny_order` add column `order_refer` enum('app-ios','app-android','app-h5') DEFAULT 'app-ios';",
    ),
);

//创建 总店 数据表结构
$upgradeschema['head']['create'] = array();

//更新 总店 数据表结构
$upgradeschema['head']['modify'] = array(
    'order'=>array(
        'order_refer'=>"alter table `tiny_order` add column `order_refer` enum('app-ios','app-android','app-h5') DEFAULT 'app-ios'",
    ),
);

return array(
    $upgradeschema,
    $upgradedata
);
/**
 *
 *  20170417 功能升级涉及内容
 *  branch
 *
 *  insert tiny_pay_plugin  `name`, `class_name`, `description`, `logo` ('微信[公众号支付]', 'wechat', '微信公众号支付', '/payments/logos/pay_wechat.gif')
 *
 *  update tiny_user
 *      `wxOpenId` varchar(100) comment 微信用户openId
 *      `wxAccessToken` varchar(200) comment 微信用户授权access_token
 *
 *
 *
 *  headstore
 *
 *  insert tiny_pay_plugin  `name`, `class_name`, `description`, `logo` ('微信[公众号支付]', 'wechat', '微信公众号支付', '/payments/logos/pay_wechat.gif')
 *
 *  update tiny_order
 *      `order_refer` enum('app-ios','app-android','app-h5') DEFAULT 'app-ios',
 *
 * 
 *  add job:
 *
 *  php /jobs/job.php statisticsJob --stattype=goodsrecommend
 *
 *  run fix job:
 *  
 *  php job.php upgradeJob --database=withoutzd --version=20170417
 *
 *  php job.php upgradeJob --database=zd --version=20170417
 *
 */
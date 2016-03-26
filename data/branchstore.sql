DROP TABLE IF EXISTS `tiny_balance_log`;
CREATE TABLE `tiny_balance_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(11) DEFAULT '0' ,
  `user_id` bigint(11) NOT NULL ,
  `type` tinyint(4) NOT NULL DEFAULT '0' ,
  `time` datetime DEFAULT NULL ,
  `amount` float(10,2) NOT NULL ,
  `amount_log` float(10,2) NOT NULL ,
  `note` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='预存款日志表';
DROP TABLE IF EXISTS `tiny_brand`;
CREATE TABLE `tiny_brand` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL ,
  `url` varchar(255) NOT NULL ,
  `logo` varchar(255) DEFAULT NULL ,
  `content` text ,
  `sort` int(11) DEFAULT '1' ,
  `seo_title` varchar(255) DEFAULT NULL ,
  `seo_keywords` varchar(255) DEFAULT NULL ,
  `seo_description` varchar(255) DEFAULT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='品牌表';
DROP TABLE IF EXISTS `tiny_cache`;
CREATE TABLE `tiny_cache` (
  `key` varchar(40) NOT NULL ,
  `content` text NOT NULL ,
  `delay` int(11) NOT NULL ,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='缓存表';
DROP TABLE IF EXISTS `tiny_customer`;
CREATE TABLE `tiny_customer` (
  `user_id` bigint(20) NOT NULL ,
  `real_name` varchar(50) DEFAULT NULL ,
  `phone` varchar(50) DEFAULT NULL ,
  `mobile` varchar(20) DEFAULT NULL ,
  `province` bigint(20) DEFAULT NULL ,
  `city` bigint(20) DEFAULT NULL ,
  `county` bigint(20) DEFAULT NULL ,
  `addr` varchar(250) DEFAULT NULL ,
  `qq` varchar(15) DEFAULT NULL ,
  `sex` tinyint(1) DEFAULT '1' ,
  `birthday` date DEFAULT NULL ,
  `group_id` int(6) DEFAULT '0' ,
  `point` int(11) DEFAULT '0' ,
  `message_ids` text ,
  `prop` text ,
  `balance` float(10,2) DEFAULT '0.00' ,
  `custom` varchar(255) DEFAULT NULL ,
  `reg_time` datetime DEFAULT NULL ,
  `login_time` datetime DEFAULT NULL ,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';
DROP TABLE IF EXISTS `tiny_doc_invoice`;
CREATE TABLE `tiny_doc_invoice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(50) DEFAULT NULL ,
  `order_id` bigint(20) NOT NULL ,
  `order_no` varchar(50) NOT NULL ,
  `admin` varchar(20) DEFAULT NULL ,
  `accept_name` varchar(50) DEFAULT NULL ,
  `province` bigint(20) DEFAULT NULL ,
  `city` bigint(20) DEFAULT NULL ,
  `county` bigint(20) DEFAULT NULL ,
  `zip` varchar(6) DEFAULT NULL ,
  `addr` varchar(250) DEFAULT NULL ,
  `phone` varchar(20) DEFAULT NULL ,
  `mobile` varchar(20) DEFAULT NULL ,
  `create_time` datetime DEFAULT NULL ,
  `express_no` varchar(50) DEFAULT NULL ,
  `express_company_id` bigint(20) DEFAULT NULL ,
  `remark` varchar(255) DEFAULT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发货单';
DROP TABLE IF EXISTS `tiny_doc_receiving`;
CREATE TABLE `tiny_doc_receiving` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL ,
  `user_id` bigint(20) NOT NULL ,
  `admin_id` bigint(20) DEFAULT NULL ,
  `amount` float(10,2) DEFAULT '0.00' ,
  `create_time` datetime DEFAULT NULL ,
  `payment_time` datetime DEFAULT NULL ,
  `doc_type` tinyint(1) DEFAULT NULL ,
  `payment_id` bigint(20) DEFAULT NULL ,
  `pay_status` tinyint(1) DEFAULT NULL ,
  `note` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收款单';
DROP TABLE IF EXISTS `tiny_doc_refund`;
CREATE TABLE `tiny_doc_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL ,
  `user_id` int(11) DEFAULT NULL ,
  `order_no` varchar(20) NOT NULL ,
  `create_time` datetime DEFAULT NULL ,
  `refund_type` tinyint(3) DEFAULT '0' ,
  `account_bank` varchar(100) DEFAULT NULL ,
  `account_name` varchar(30) DEFAULT NULL ,
  `refund_account` varchar(50) DEFAULT NULL ,
  `admin_id` int(11) DEFAULT NULL ,
  `pay_status` tinyint(3) DEFAULT '0' ,
  `content` text ,
  `handling_idea` text ,
  `handling_time` datetime DEFAULT NULL ,
  `channel` varchar(50) DEFAULT NULL ,
  `bank_account` varchar(255) DEFAULT NULL ,
  `amount` float(10,2) DEFAULT '0.00' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退款单';
DROP TABLE IF EXISTS `tiny_doc_returns`;
CREATE TABLE `tiny_doc_returns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL ,
  `admin_id` int(11) DEFAULT NULL ,
  `order_id` int(11) DEFAULT NULL ,
  `order_no` varchar(50) NOT NULL ,
  `reason` varchar(255) DEFAULT NULL ,
  `province` bigint(20) DEFAULT NULL ,
  `city` bigint(20) DEFAULT NULL ,
  `county` binary(1) DEFAULT NULL ,
  `zip` varchar(6) DEFAULT NULL ,
  `addr` varchar(250) DEFAULT NULL ,
  `name` varchar(50) DEFAULT NULL ,
  `phone` varchar(20) DEFAULT NULL ,
  `mobile` varchar(20) DEFAULT NULL ,
  `create_time` datetime DEFAULT NULL ,
  `express_no` varchar(255) DEFAULT NULL ,
  `express` varchar(255) DEFAULT NULL ,
  `handling_idea` varchar(255) DEFAULT NULL ,
  `status` tinyint(3) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='售后单';
DROP TABLE IF EXISTS `tiny_goods`;
CREATE TABLE `tiny_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT ,
  `name` varchar(50) NOT NULL ,
  `branchstore_goods_name` varchar(50) DEFAULT NULL COMMENT '分店自定义商品名称',
  `category_id` bigint(20) NOT NULL ,
  `goods_no` varchar(20) NOT NULL ,
  `pro_no` varchar(20) NOT NULL ,
  `type_id` bigint(20) NOT NULL ,
  `brand_id` bigint(20) DEFAULT NULL ,
  `unit` varchar(10) NOT NULL ,
  `content` text ,
  `img` varchar(255) DEFAULT NULL ,
  `imgs` text ,
  `tag_ids` varchar(255) DEFAULT NULL ,
  `sell_price` float(10,2) NOT NULL COMMENT '销售价',
  `branchstore_sell_price` float(10,2) DEFAULT NULL COMMENT '分店自定义销售价',
  `trade_price` float(10,2) DEFAULT '0.00' COMMENT '批发价',
  `market_price` float(10,2) NOT NULL ,
  `cost_price` float(10,2) NOT NULL ,
  `create_time` datetime DEFAULT NULL ,
  `store_nums` int(11) DEFAULT '0' ,
  `warning_line` int(11) DEFAULT '0' ,
  `seo_title` varchar(255) DEFAULT NULL ,
  `seo_keywords` varchar(255) DEFAULT NULL ,
  `seo_description` varchar(255) DEFAULT NULL ,
  `weight` int(11) DEFAULT '0' ,
  `point` int(11) DEFAULT '0' ,
  `visit` int(11) DEFAULT '0' ,
  `favorite` int(11) DEFAULT '0' ,
  `sort` int(11) DEFAULT '1' ,
  `specs` text ,
  `attrs` text ,
  `prom_id` bigint(20) DEFAULT '0' ,
  `is_online` tinyint(1) DEFAULT '0' ,
  `sale_protection` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';
DROP TABLE IF EXISTS `tiny_goods_attr`;
CREATE TABLE `tiny_goods_attr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `show_type` int(11) DEFAULT '0' ,
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性';
DROP TABLE IF EXISTS `tiny_goods_category`;
CREATE TABLE `tiny_goods_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '' ,
  `alias` varchar(200) NOT NULL DEFAULT '' ,
  `parent_id` bigint(20) DEFAULT '0' ,
  `type_id` bigint(20) DEFAULT NULL ,
  `count` bigint(20) DEFAULT '0' ,
  `path` text ,
  `img` varchar(255) DEFAULT NULL,
  `imgs` text,
  `sort` int(11) DEFAULT NULL ,
  `seo_title` varchar(255) DEFAULT NULL ,
  `seo_keywords` varchar(255) DEFAULT NULL ,
  `seo_description` varchar(255) DEFAULT NULL ,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类关联表';
DROP TABLE IF EXISTS `tiny_goods_spec`;
CREATE TABLE `tiny_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL ,
  `value` text NOT NULL ,
  `type` tinyint(1) DEFAULT '1' ,
  `note` varchar(255) DEFAULT NULL ,
  `is_del` tinyint(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品规格';
DROP TABLE IF EXISTS `tiny_goods_type`;
CREATE TABLE `tiny_goods_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `attr` text,
  `spec` text,
  `brand` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品类型';
DROP TABLE IF EXISTS `tiny_manager`;
CREATE TABLE `tiny_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL ,
  `roles` varchar(20) NOT NULL ,
  `password` varchar(32) NOT NULL ,
  `validcode` varchar(10) NOT NULL ,
  `last_ip` varchar(40) DEFAULT NULL ,
  `last_login` datetime DEFAULT NULL ,
  `is_lock` int(1) DEFAULT NULL ,
  `distributor_id` bigint(20) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `phone` varchar(20) DEFAULT NULL COMMENT '电话号码',
  `province` bigint(20) NOT NULL COMMENT '省份',
  `city` bigint(20) NOT NULL COMMENT '城市',
  `county` bigint(20) NOT NULL COMMENT '县',
  `zip` varchar(6) DEFAULT NULL COMMENT '邮编',
  `addr` varchar(250) DEFAULT NULL COMMENT '地址',
  `site_name` varchar(255) DEFAULT NULL COMMENT '站点名称',
  `site_logo` varchar(255) DEFAULT NULL COMMENT '站点logo',
  `site_icp` varchar(100) DEFAULT NULL COMMENT '公司/备案号',
  `site_url` varchar(255) NOT NULL COMMENT '站点网址',
  `site_ios_url` varchar(255) NOT NULL COMMENT 'ios下载地址',
  `site_android_url` varchar(255) NOT NULL COMMENT 'android下载地址',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `site_keyword` varchar(255) NOT NULL COMMENT '关键字',
  `site_description` varchar(255) NOT NULL COMMENT '描述信息',
  `deposit` float(10,2) DEFAULT '0.00' COMMENT '预存款',
  `income` float(10,2) DEFAULT '0.00' COMMENT '收益',
  `catids` varchar(200) NOT NULL DEFAULT '' COMMENT '授权分类',
  `register_time` int(10) NOT NULL COMMENT '加入时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';
DROP TABLE IF EXISTS `tiny_mobile_code`;

CREATE TABLE `tiny_mobile_code` (
  `mobile` varchar(20) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `send_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='手机验证码';
DROP TABLE IF EXISTS `tiny_msg_template`;
CREATE TABLE `tiny_msg_template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL ,
  `title` varchar(255) NOT NULL ,
  `variable` varchar(255) NOT NULL ,
  `content` text NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信模板';
DROP TABLE IF EXISTS `tiny_oauth`;
CREATE TABLE `tiny_oauth` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `class_name` varchar(20) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `app_key` varchar(32) DEFAULT NULL,
  `app_secret` varchar(50) DEFAULT NULL,
  `authorize` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='oauth认证表';
DROP TABLE IF EXISTS `tiny_oauth_user`;
CREATE TABLE `tiny_oauth_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `oauth_type` enum('QqOAuth','SinaOAuth','RenrenOAuth','WeixinOAuth','DoubanOAuth') DEFAULT NULL,
  `open_id` varchar(32) DEFAULT NULL,
  `open_name` varchar(20) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `post_time` varchar(10) DEFAULT NULL,
  `expires` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='oauth认证用户表';
DROP TABLE IF EXISTS `tiny_order`;
CREATE TABLE `tiny_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL ,
  `user_id` bigint(20) NOT NULL ,
  `pay_code` varchar(255) DEFAULT NULL ,
  `payment` bigint(20) NOT NULL ,
  `express` bigint(20) DEFAULT NULL ,
  `status` tinyint(1) DEFAULT '1' ,
  `distr_balance_status` tinyint(1) DEFAULT '0' COMMENT '总店扣除分销商预存款之后,状态 0:未处理 1:已处理',
  `pay_status` tinyint(1) DEFAULT '0' ,
  `delivery_status` tinyint(1) DEFAULT '0' ,
  `accept_name` varchar(20) DEFAULT NULL ,
  `phone` varchar(20) DEFAULT NULL ,
  `mobile` varchar(20) DEFAULT NULL ,
  `province` bigint(20) DEFAULT NULL ,
  `city` bigint(20) DEFAULT NULL ,
  `county` bigint(20) DEFAULT NULL ,
  `addr` varchar(250) DEFAULT NULL ,
  `zip` varchar(6) DEFAULT NULL ,
  `payable_amount` float(10,2) DEFAULT NULL ,
  `real_amount` float(10,2) DEFAULT '0.00' ,
  `payable_freight` float(10,2) DEFAULT '0.00' ,
  `real_freight` float(10,2) DEFAULT '0.00' ,
  `pay_time` datetime DEFAULT NULL ,
  `send_time` datetime DEFAULT NULL ,
  `create_time` datetime DEFAULT NULL ,
  `completion_time` datetime DEFAULT NULL ,
  `user_remark` varchar(255) DEFAULT NULL ,
  `admin_remark` varchar(255) DEFAULT NULL ,
  `handling_fee` float(10,2) DEFAULT '0.00' ,
  `is_invoice` tinyint(1) DEFAULT '0' ,
  `invoice_title` varchar(100) DEFAULT NULL ,
  `taxes` float(10,2) DEFAULT '0.00' ,
  `prom_id` bigint(20) DEFAULT '0' ,
  `prom` text ,
  `discount_amount` float(10,2) DEFAULT '0.00' ,
  `adjust_amount` float(10,2) DEFAULT '0.00' ,
  `adjust_note` varchar(255) DEFAULT NULL ,
  `order_amount` float(10,2) DEFAULT '0.00' ,
  `is_print` varchar(255) DEFAULT NULL ,
  `accept_time` varchar(80) DEFAULT NULL ,
  `point` int(5) unsigned DEFAULT '0' ,
  `voucher_id` bigint(20) DEFAULT '0' ,
  `voucher` text ,
  `type` int(4) unsigned DEFAULT '0' ,
  `trading_info` varchar(255) DEFAULT NULL,
  `is_del` tinyint(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';
DROP TABLE IF EXISTS `tiny_order_goods`;
CREATE TABLE `tiny_order_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL ,
  `goods_id` bigint(20) DEFAULT NULL ,
  `product_id` bigint(20) DEFAULT NULL ,
  `goods_price` float(10,2) DEFAULT '0.00' ,
  `real_price` float(10,2) DEFAULT '0.00' ,
  `trade_price` float(10,2) DEFAULT '0.00' COMMENT '批发价',
  `goods_nums` int(11) DEFAULT '1' ,
  `goods_weight` float DEFAULT '0' ,
  `shipments` int(11) DEFAULT '0' ,
  `prom_goods` text ,
  `spec` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单商品表';
DROP TABLE IF EXISTS `tiny_order_log`;
CREATE TABLE `tiny_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL ,
  `user` varchar(20) DEFAULT NULL ,
  `action` varchar(20) DEFAULT NULL ,
  `addtime` datetime DEFAULT NULL ,
  `result` varchar(10) DEFAULT NULL ,
  `note` varchar(100) DEFAULT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单日志表';
DROP TABLE IF EXISTS `tiny_products`;
CREATE TABLE `tiny_products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT ,
  `goods_id` bigint(20) NOT NULL ,
  `pro_no` varchar(20) DEFAULT NULL ,
  `spec` text ,
  `store_nums` int(11) DEFAULT '0' ,
  `warning_line` int(11) DEFAULT '0' ,
  `branchstore_sell_price` float(10,2) DEFAULT '0.00' COMMENT '分店自定义销售价',
  `trade_price` float(10,2) DEFAULT '0.00' COMMENT '批发价',
  `market_price` float(10,2) DEFAULT '0.00' ,
  `sell_price` float(10,2) DEFAULT '0.00' COMMENT '销售价',
  `cost_price` float(10,2) DEFAULT '0.00' ,
  `weight` int(11) DEFAULT NULL ,
  `specs_key` varchar(255) DEFAULT NULL ,
  PRIMARY KEY (`id`),
  KEY `GOODS_ID` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='货品表';
DROP TABLE IF EXISTS `tiny_reset_password`;
CREATE TABLE `tiny_reset_password` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `safecode` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='恢复密码表';
DROP TABLE IF EXISTS `tiny_ship`;
CREATE TABLE `tiny_ship` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ship_name` varchar(255) NOT NULL ,
  `ship_user_name` varchar(255) NOT NULL ,
  `province` bigint(20) NOT NULL ,
  `city` bigint(20) NOT NULL ,
  `county` bigint(20) NOT NULL ,
  `zip` varchar(6) NOT NULL ,
  `addr` varchar(255) NOT NULL ,
  `mobile` varchar(20) DEFAULT NULL ,
  `phone` varchar(20) DEFAULT NULL ,
  `is_default` tinyint(1) DEFAULT '0' ,
  `note` varchar(255) DEFAULT NULL ,
  `addtime` datetime DEFAULT NULL ,
  `is_del` tinyint(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发货表';
DROP TABLE IF EXISTS `tiny_spec_attr`;
CREATE TABLE `tiny_spec_attr` (
  `goods_id` bigint(20) NOT NULL,
  `key` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  UNIQUE KEY `GOODS_SPEC_ATTR` (`goods_id`,`key`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='规格表';
DROP TABLE IF EXISTS `tiny_spec_value`;
CREATE TABLE `tiny_spec_value` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `spec_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `img` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ID_NAME` (`spec_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='规格值表';
DROP TABLE IF EXISTS `tiny_tags`;
CREATE TABLE `tiny_tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL ,
  `num` bigint(20) DEFAULT '0' ,
  `sort` int(11) DEFAULT '0' ,
  `is_hot` int(1) DEFAULT '0' ,
  PRIMARY KEY (`id`),
  KEY `index_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';
DROP TABLE IF EXISTS `tiny_user`;
CREATE TABLE `tiny_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL ,
  `password` char(32) NOT NULL ,
  `email` varchar(250) NOT NULL ,
  `head_pic` varchar(250) DEFAULT NULL ,
  `validcode` varchar(40) NOT NULL ,
  `status` tinyint(1) DEFAULT '1' ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `USEREMAIL` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `tiny_withdraw`;
CREATE TABLE `tiny_withdraw` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL ,
  `amount` float(10,2) DEFAULT '0.00' ,
  `name` varchar(255) DEFAULT NULL ,
  `type_name` varchar(255) DEFAULT NULL ,
  `account` varchar(255) DEFAULT NULL ,
  `note` varchar(255) DEFAULT NULL ,
  `re_note` varchar(255) DEFAULT NULL ,
  `time` datetime DEFAULT NULL ,
  `status` tinyint(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='体现申请表';
DROP TABLE IF EXISTS `tiny_attr_value`;
CREATE TABLE `tiny_attr_value` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attr_id` bigint(20) NOT NULL ,
  `name` varchar(50) NOT NULL ,
  `sort` int(11) DEFAULT '1' ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ID_NAME` (`attr_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `tiny_address`;
CREATE TABLE `tiny_address` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL ,
  `accept_name` varchar(20) NOT NULL ,
  `mobile` varchar(20) DEFAULT NULL ,
  `phone` varchar(20) DEFAULT NULL ,
  `province` bigint(20) NOT NULL ,
  `city` bigint(20) NOT NULL ,
  `county` bigint(20) NOT NULL ,
  `zip` varchar(6) DEFAULT NULL ,
  `addr` varchar(250) DEFAULT NULL ,
  `is_default` tinyint(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ;
INSERT INTO `tiny_address` (`id`,`user_id`,`accept_name`,`mobile`,`phone`,`province`,`city`,`county`,`zip`,`addr`,`is_default`) VALUES ('1','1','晓飞 宁','13589100333','13589100475','370000','370100','370102','250000','山东省ddddddd','1');
DROP TABLE IF EXISTS `tiny_prom_goods`;
CREATE TABLE `tiny_prom_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL ,
  `type` int(2) NOT NULL DEFAULT '0' ,
  `expression` varchar(100) NOT NULL ,
  `description` text ,
  `start_time` datetime NOT NULL ,
  `end_time` datetime NOT NULL ,
  `goods_id` bigint(20) DEFAULT NULL ,
  `is_close` tinyint(1) DEFAULT '0' ,
  `group` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ;
INSERT INTO `tiny_prom_goods` (`id`,`name`,`type`,`expression`,`description`,`start_time`,`end_time`,`goods_id`,`is_close`,`group`) VALUES ('1','开业庆典','1','5','','2014-05-04 10:05:05','2014-12-31 10:05:08','0','0','0'),('2','送代金券','3','1','','2014-05-04 10:08:01','2014-12-31 10:08:03','0','0','0');
DROP TABLE IF EXISTS `tiny_review`;
CREATE TABLE `tiny_review` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `goods_id` bigint(20) NOT NULL ,
  `order_no` varchar(20) DEFAULT NULL ,
  `user_id` bigint(20) NOT NULL ,
  `content` text ,
  `point` tinyint(1) DEFAULT '5' ,
  `status` tinyint(1) DEFAULT '0' ,
  `buy_time` datetime DEFAULT NULL ,
  `comment_time` date DEFAULT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `tiny_ask`;
CREATE TABLE `tiny_ask` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL ,
  `user_id` bigint(20) DEFAULT NULL ,
  `goods_id` bigint(20) DEFAULT NULL ,
  `admin_id` bigint(20) DEFAULT NULL ,
  `content` varchar(255) DEFAULT NULL ,
  `ask_time` datetime DEFAULT NULL ,
  `reply_time` datetime DEFAULT NULL ,
  `type` int(3) DEFAULT '0' ,
  `status` tinyint(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `tiny_grade`;
CREATE TABLE `tiny_grade` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT ,
  `name` varchar(20) NOT NULL ,
  `money` bigint(20) NOT NULL ,
  `message_ids` varchar(255) DEFAULT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `tiny_payment`;
CREATE TABLE `tiny_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `plugin_id` varchar(32) NOT NULL ,
  `pay_name` varchar(100) NOT NULL ,
  `config` text,
  `client_type` int(1) DEFAULT '0',
  `description` text ,
  `note` text ,
  `pay_fee` float(10,2) DEFAULT '0.00' ,
  `fee_type` tinyint(1) DEFAULT '0' ,
  `sort` int(11) DEFAULT NULL ,
  `status` int(1) DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `tiny_pay_plugin`;
CREATE TABLE `tiny_pay_plugin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL ,
  `class_name` varchar(30) NOT NULL ,
  `description` text ,
  `logo` varchar(255) DEFAULT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ;
INSERT INTO `tiny_pay_plugin` (`id`,`name`,`class_name`,`description`,`logo`) VALUES ('1','预存款支付','balance','预存款是客户在您网站上的虚拟资金帐户。','/payments/logos/pay_deposit.gif'),('5','腾讯财付通','tenpay','费率最低至<span style=\"color: #FF0000;font-weight: bold;\">0.61%</span>，并赠送价值千元企业QQ <a style=\"color:blue\" href=\"http://union.tenpay.com/mch/mch_register.shtml\" target=\"_blank\">立即申请</a>','/payments/logos/pay_tenpay.gif'),('2','支付宝[担保交易]','alipaytrad','淘宝买家最熟悉的付款方式：买家先将交易资金存入支付宝并通知卖家发货，买家确认收货后资金自动进入卖家支付宝账户，完成交易 <a style=\"color:blue\" href=\"https://b.alipay.com/order/productDetail.htm?productId=2012111200373121\" target=\"_blank\">立即申请</a>','/payments/logos/pay_alipaytrad.gif'),('3','支付宝[双向接口]','alipay','买家付款时，可选择担保交易或即时到账中的任一支付方式进行付款，完成交易。<a style=\"color:blue\" href=\"https://b.alipay.com/order/productDetail.htm?productId=2012111300373136\" target=\"_blank\">立即申请</a>','/payments/logos/pay_alipay.gif'),('6','PayPal','paypal','PayPal 是全球最大的在线支付平台，同时也是目前全球贸易网上支付标准。','/payments/logos/pay_paypal.gif'),('4','支付宝[即时到帐]','alipaydirect','网上交易时，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a style=\"color:blue\" href=\"https://b.alipay.com/order/productDetail.htm?productId=2012111200373124\" target=\"_blank\">立即申请</a>','/payments/logos/pay_alipay.gif'),(7,'货到付款','received','客户收到商品时，再进行付款，让客户更放心。','/payments/logos/pay_received.gif'),(8,'支付宝[银行支付]','alipaygateway',NULL,'/payments/logos/pay_alipay.gif'),(9,'支付宝[手机支付]','alipaymobile',NULL,'/payments/logos/pay_alipaymobile.gif');
DROP TABLE IF EXISTS `tiny_help`;
CREATE TABLE `tiny_help` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` longtext,
  `category_id` bigint(20) NOT NULL,
  `summary` varchar(255) DEFAULT '',
  `status` int(2) DEFAULT '0',
  `top` int(1) DEFAULT '0',
  `publish_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `count` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
INSERT INTO `tiny_help` (`id`,`title`,`content`,`category_id`,`summary`,`status`,`top`,`publish_time`,`count`) VALUES ('3','账户注册','<h5 style=\"color:#666666;font-family:Tahoma;\">\r\n	<br /></h5>','1','','0','0','2014-02-10 22:36:35','0'),('5','购物流程','','1','','0','0','2014-03-07 14:36:45','0'),('6','积分制度','<h5 style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	一、积分制度\r\n</h5>\r\n<p class=\"pl20\" style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	您在TinyShop商城购物消费，或参加活动等，即可获得<span style=\"color:#666666;font-family:Tahoma, simsun;font-size:14px;line-height:21px;background-color:#FFFFFF;\">TinyShop商城</span>的积分。积分可以兑换TinyShop商城的代金卷，在购买商品进行确认订单的时候，可能使用满足条件的代金卷。\r\n</p>\r\n<h5 style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	二、积分获取\r\n</h5>\r\n<p class=\"pl20\" style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	您可以通过以下几种方式，获取积分：\r\n</p>\r\n<p class=\"pl20\" style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	a)每个商品都有不同的对应积分，购买后都可以得到对应的积分。<br />\r\nb)当满足了订单促销活动时，如果是积分N倍活动，则可以得到，商品总分N倍的积分。\r\n</p>\r\n<h5 style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	三、积分的使用\r\n</h5>\r\n<p class=\"pl20\" style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	您可以采取以下几种方式，使用积分：\r\n</p>\r\n<p class=\"pl20\" style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	<b class=\"bor_tl\">兑换代金卷</b><br />\r\n积分可用来兑换TinyShop商城的代金卷。\r\n</p>\r\n<p class=\"pl20\" style=\"font-size:14px;color:#666666;font-family:Tahoma, simsun;background-color:#FFFFFF;\">\r\n	<b class=\"bor_tl\">会员升级</b><br />\r\n积分累计和新增积分到特定数额，即可自动提升会员等级。不同等级会员，可以享受不同优惠。\r\n</p>','1','','0','0','2014-03-07 14:38:30','0'),('7','配送范围','','3','','0','0','2014-03-07 15:25:41','0'),('8','余额支付','','5','','0','0','2014-03-07 15:34:36','0'),('9','退款说明','','6','','0','0','2014-03-07 15:35:07','0'),('10','联系客服','','7','','0','0','2014-03-07 15:35:33','0'),('11','找回密码','','7','','0','0','2014-03-07 15:35:46','0'),('12','常见问题','','7','','0','0','2014-03-07 15:35:58','0'),('13','售后保障','<div style=\"color:#666666;font-family:Arial;\">\r\n	<strong>服务承诺：</strong><br />\r\n商城向您保证所售商品均为正品行货，自营商品开具机打发票或电子发票。凭质保证书及商城发票，可享受全国联保服务（奢侈品、钟表除外；奢侈品、钟表由联系保修，享受法定三包售后服务），与您亲临商场选购的商品享受相同的质量保证。商城还为您提供具有竞争力的商品价格和运费政策，请您放心购买！ <br />\r\n注：因厂家会在没有任何提前通知的情况下更改产品包装、产地或者一些附件，本司不能确保客户收到的货物与商城图片、产地、附件说明完全一致。只能确保为原厂正货！并且保证与当时市场上同样主流新品一致。若本商城没有及时更新，请大家谅解！\r\n</div>\r\n<div style=\"color:#666666;font-family:Arial;\">\r\n	<strong>权利声明：</strong><br />\r\n商城上的所有商品信息、客户评价、商品咨询、网友讨论等内容，是商城重要的经营资源，未经许可，禁止非法转载使用。\r\n	<p>\r\n		<b>注：</b>本站商品信息均来自于厂商，其真实性、准确性和合法性由信息拥有者（厂商）负责。本站不提供任何保证，并不承担任何法律责任。\r\n	</p>\r\n</div>','6','','0','0','2014-05-04 10:38:55','0'),('14','用户注册协议','<p>\r\n	演示内容，请尽快完善用户注册协议。\r\n</p>',7,'',0,0,'2015-04-02 14:25:42',0);
DROP TABLE IF EXISTS `tiny_sync_queue`;
CREATE TABLE `tiny_sync_queue` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `content` longtext NOT NULL COMMENT '扫描人',
  `distributor_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '分销商id',
  `inserttime` int(11) DEFAULT NULL COMMENT '插入时间',
  `modifytime` int(11) DEFAULT NULL COMMENT '执行时间',
  `level` int(1) NOT NULL DEFAULT 0 COMMENT '优先级 0:普通 1:中 2:高',
  `sync_direct` enum('headtobranch', 'branchtohead') DEFAULT 'headtobranch',
  `domain` varchar(30) NOT NULL COMMENT '域名',
  `action` enum('add', 'update','del') DEFAULT 'add',
  `status` enum('ready', 'syncing','success','fail') DEFAULT 'ready',
  `action_type` enum('normal', 'openshop') DEFAULT 'normal',
  `sync_type` enum('goods', 'brand','category','distrInfo','goods_type','payment','products','spec','tag') DEFAULT 'goods',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商预存款日志表';
DROP TABLE IF EXISTS `tiny_distributor_depost`;
CREATE TABLE `tiny_distributor_depost` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `memo` varchar(200) NOT NULL COMMENT '备注',
  `op_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '操作人id',
  `op_time` int(11) DEFAULT NULL COMMENT '操作时间',
  `money` float(10,2) NOT NULL COMMENT '操作金额',
  `action` enum('add', 'minus') DEFAULT 'add' COMMENT '交易类型 +、-',
  `op_ip` varchar(40) DEFAULT NULL COMMENT '操作ip地址',
  `op_name` varchar(200) NOT NULL COMMENT '操作人名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商预存款日志表';
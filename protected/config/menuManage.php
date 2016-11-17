<?php return array (
  'branchstore' => 
  array (
    'menu' => 
    array (
      'order' => 
      array (
        'link' => '/order/order_list',
        'name' => '订单管理',
      ),
      'goods' => 
      array (
        'link' => '/goods/goods_list',
        'name' => '商品管理',
      ),
      'customer' => 
      array (
        'link' => '/customer/customer_list',
        'name' => '会员管理',
      ),
      'system' => 
      array (
        'link' => '/admin/index',
        'name' => '系统设置',
      ),
    ),
    'submenu' => 
    array (
      'config' => 
      array (
        'name' => '网店设置',
        'parent' => 'system',
      ),
      'goods' => 
      array (
        'name' => '商品管理',
        'parent' => 'goods',
      ),
      'customer' => 
      array (
        'name' => '会员管理',
        'parent' => 'customer',
      ),
      'balance' => 
      array (
        'name' => '会员资金',
        'parent' => 'customer',
      ),
      'ask_reviews' => 
      array (
        'name' => '咨询与评价',
        'parent' => 'customer',
      ),
      'order' => 
      array (
        'name' => '订单管理',
        'parent' => 'order',
      ),
      'receipt' => 
      array (
        'name' => '单据管理',
        'parent' => 'order',
      ),
    ),
    'nodes' => 
    array (
      '/admin/index' => 
      array (
        'name' => '管理首页',
        'parent' => 'config',
      ),
      '/admin/theme_list' => 
      array (
        'name' => '主题设置',
        'parent' => 'config',
      ),
      '/admin/config_globals_branchstore' => 
      array (
        'name' => '站点设置',
        'parent' => 'config',
      ),
      '/distributor/deposit_list' => 
      array (
        'name' => '分销商预存款日志',
        'parent' => 'config',
      ),
      '/admin/cservice' => 
      array (
        'name' => '客户服务',
        'parent' => 'config',
      ),
//      '/content/adposition_list' =>
//      array (
//         'name' => '广告位管理',
//         'parent' => 'config',
//      ),
//      '/content/adposition_edit' =>
//      array (
//         'name' => '编辑广告位',
//         'parent' => 'config',
//      ),
      '/admin/clear' => 
      array (
        'name' => '清除缓存',
        'parent' => 'safe',
      ),
      '/admin/log_operation_list' => 
      array (
        'name' => '操作日志',
        'parent' => 'safe',
      ),
      '/goods/goods_list' => 
      array (
        'name' => '商品列表',
        'parent' => 'goods',
      ),
      '/goods/goods_edit' => 
      array (
        'name' => '商品编辑',
        'parent' => 'goods',
      ),
      '/goods/goods_category_list' => 
      array (
        'name' => '分类管理',
        'parent' => 'goods',
      ),
      '/customer/customer_list' => 
      array (
        'name' => '会员管理',
        'parent' => 'customer',
      ),
      '/customer/customer_edit' => 
      array (
        'name' => '编辑会员',
        'parent' => 'customer',
      ),
      '/customer/withdraw_list' => 
      array (
        'name' => '提现申请',
        'parent' => 'balance',
      ),
      '/customer/balance_list' => 
      array (
        'name' => '资金日志',
        'parent' => 'balance',
      ),
      '/customer/review_list' => 
      array (
        'name' => '商品评价',
        'parent' => 'ask_reviews',
      ),
      '/customer/ask_list' => 
      array (
        'name' => '商品咨询',
        'parent' => 'ask_reviews',
      ),
      '/customer/ask_edit' => 
      array (
        'name' => '咨询回复',
        'parent' => 'ask_reviews',
      ),
      '/customer/message_list' => 
      array (
        'name' => '信息管理',
        'parent' => 'ask_reviews',
      ),
      '/customer/message_edit' => 
      array (
        'name' => '信息发送',
        'parent' => 'ask_reviews',
      ),
      '/customer/notify_list' => 
      array (
        'name' => '到货通知',
        'parent' => 'ask_reviews',
      ),
      '/order/order_list' => 
      array (
        'name' => '订单列表',
        'parent' => 'order',
      ),
      '/order/doc_receiving_list' => 
      array (
        'name' => '收款单',
        'parent' => 'receipt',
      ),
      '/order/doc_invoice_list' => 
      array (
        'name' => '发货单',
        'parent' => 'receipt',
      ),
      '/order/doc_refund_list' => 
      array (
        'name' => '退款单',
        'parent' => 'receipt',
      ),
      '/order/doc_returns_list' => 
      array (
        'name' => '售后单',
        'parent' => 'receipt',
      ),
    ),
  ),
  'headstore' => 
  array (
    'menu' => 
    array (
      'goods' => 
      array (
        'link' => '/goods/goods_list',
        'name' => '商品管理',
      ),
      'order' => 
      array (
        'link' => '/order/order_list',
        'name' => '订单管理',
      ),
      'customer' => 
      array (
        'link' => '/customer/customer_list',
        'name' => '会员管理',
      ),
      'distributor' => 
      array (
        'link' => '/distributor/distributor_list',
        'name' => '分销商管理',
      ),
      'content' => 
      array (
        'link' => '/content/article_list',
        'name' => '内容管理',
      ),
      'count'=>
      array(
        'link'=>'/count/index',
        'name'=>'统计报表'
      ),
      'system' => 
      array (
        'link' => '/admin/index',
        'name' => '系统设置',
      ),
    ),
    'submenu' => 
    array (
      'config' => 
      array (
        'name' => '网店设置',
        'parent' => 'system',
      ),
      'third' => 
      array (
        'name' => '第三方整合',
        'parent' => 'system',
      ),
      'delivery' => 
      array (
        'name' => '支付与配送',
        'parent' => 'system',
      ),
      'safe' => 
      array (
        'name' => '安全管理',
        'parent' => 'system',
      ),
      'article' => 
      array (
        'name' => '文章管理',
        'parent' => 'content',
      ),
      'help' => 
      array (
        'name' => '帮助中心',
        'parent' => 'content',
      ),
      'banner' => 
      array (
        'name' => '内容管理',
        'parent' => 'content',
      ),
      'goods' => 
      array (
        'name' => '商品管理',
        'parent' => 'goods',
      ),
      'customer' => 
      array (
        'name' => '会员管理',
        'parent' => 'customer',
      ),
      'order' => 
      array (
        'name' => '订单管理',
        'parent' => 'order',
      ),
      'distributor' => 
      array (
        'name' => '分销商管理',
        'parent' => 'distributor',
      ),
      'receipt' => 
      array (
        'name' => '单据管理',
        'parent' => 'order',
      ),
      'express' => 
      array (
        'name' => '快递单配置',
        'parent' => 'order',
      ),
      'scannersetting' => 
      array (
        'name' => '扫描枪配置',
        'parent' => 'order',
      ),
      'count'=>
      array(
            'name'=>'销售统计 (开发中)',
            'parent'=>'count'
      ),
    ),
    'nodes' => 
    array (
      '/admin/index' => 
      array (
        'name' => '管理首页',
        'parent' => 'config',
      ),
      '/admin/theme_list' => 
      array (
        'name' => '主题设置',
        'parent' => 'config',
      ),
      '/admin/config_globals_headstore' => 
      array (
        'name' => '站点设置',
        'parent' => 'config',
      ),
      '/admin/config_other_headstore' => 
      array (
        'name' => '其它配置',
        'parent' => 'config',
      ),
      '/admin/config_email_headstore' => 
      array (
        'name' => '邮箱配置',
        'parent' => 'config',
      ),
      '/admin/msg_template_list' => 
      array (
        'name' => '信息模板',
        'parent' => 'config',
      ),
      '/admin/image_manage' => 
      array (
        'name' => '图片库管理',
        'parent' => 'config',
      ),
      '/admin/msg_template_edit' => 
      array (
        'name' => '信息模板编辑',
        'parent' => 'config',
      ),
      '/admin/notice_template_list' => 
      array (
        'name' => '提醒管理',
        'parent' => 'config',
      ),
      '/admin/notice_template_edit' => 
      array (
        'name' => '提醒编辑',
        'parent' => 'config',
      ),
      '/admin/oauth_list' => 
      array (
        'name' => '开放登录',
        'parent' => 'third',
      ),
      '/admin/oauth_edit' => 
      array (
        'name' => '开放登录编辑',
        'parent' => 'third',
      ),
      '/admin/class_config_list' => 
      array (
        'name' => '第三方列表',
        'parent' => 'third',
      ),
      '/admin/class_config_edit' => 
      array (
        'name' => '第三方配制编辑',
        'parent' => 'third',
      ),
      '/admin/payment_list' => 
      array (
        'name' => '支付方式',
        'parent' => 'delivery',
      ),
      '/admin/payment_edit' => 
      array (
        'name' => '编辑支付方式',
        'parent' => 'delivery',
      ),
      '/admin/zoning_list' => 
      array (
        'name' => '区域划分',
        'parent' => 'delivery',
      ),
      '/admin/area_list' => 
      array (
        'name' => '地区管理',
        'parent' => 'delivery',
      ),
      '/admin/fare_list' => 
      array (
        'name' => '运费模板',
        'parent' => 'delivery',
      ),
      '/admin/fare_edit' => 
      array (
        'name' => '运费模板编辑',
        'parent' => 'delivery',
      ),
      '/admin/express_company_list' => 
      array (
        'name' => '快递公司',
        'parent' => 'delivery',
      ),
      '/admin/express_company_edit' => 
      array (
        'name' => '快递公司编辑',
        'parent' => 'delivery',
      ),
      '/admin/manager_list' => 
      array (
        'name' => '管理员',
        'parent' => 'safe',
      ),
      '/admin/manager_edit' => 
      array (
        'name' => '编辑管理员',
        'parent' => 'safe',
      ),
      '/admin/roles_list' => 
      array (
        'name' => '角色管理',
        'parent' => 'safe',
      ),
      '/admin/roles_edit' => 
      array (
        'name' => '角色编辑',
        'parent' => 'safe',
      ),
      '/admin/log_operation_list' => 
      array (
        'name' => '操作日志',
        'parent' => 'safe',
      ),
      '/content/article_list' => 
      array (
        'name' => '全部文章',
        'parent' => 'article',
      ),
      '/content/article_edit' => 
      array (
        'name' => '文章编辑',
        'parent' => 'article',
      ),
      '/content/category_list' => 
      array (
        'name' => '分类管理',
        'parent' => 'article',
      ),
      '/content/category_edit' => 
      array (
        'name' => '编辑分类',
        'parent' => 'article',
      ),
      '/content/help_list' => 
      array (
        'name' => '全部帮助',
        'parent' => 'help',
      ),
      '/content/help_edit' => 
      array (
        'name' => '帮助编辑',
        'parent' => 'help',
      ),
      '/content/help_category_list' => 
      array (
        'name' => '帮助分类管理',
        'parent' => 'help',
      ),
      '/content/help_category_edit' => 
      array (
        'name' => '编辑帮助分类',
        'parent' => 'help',
      ),
      '/content/ad_list' => 
      array (
        'name' => '广告管理',
        'parent' => 'banner',
      ),
      '/content/ad_edit' => 
      array (
        'name' => '编辑广告',
        'parent' => 'banner',
      ),
      '/content/adposition_list' =>
        array (
            'name' => '广告位管理',
            'parent' => 'banner',
        ),
      '/content/adposition_edit' =>
        array (
            'name' => '编辑广告位',
            'parent' => 'banner',
        ),
      '/content/tags_list' => 
      array (
        'name' => '标签管理',
        'parent' => 'banner',
      ),
      '/content/nav_list' => 
      array (
        'name' => '导航管理',
        'parent' => 'banner',
      ),
      '/content/nav_edit' => 
      array (
        'name' => '导航管理',
        'parent' => 'banner',
      ),
      '/goods/goods_list' => 
      array (
        'name' => '商品列表',
        'parent' => 'goods',
      ),
      '/goods/goods_edit' => 
      array (
        'name' => '商品编辑',
        'parent' => 'goods',
      ),
      '/goods/goods_category_list' => 
      array (
        'name' => '分类管理',
        'parent' => 'goods',
      ),
      '/goods/goods_category_edit' => 
      array (
        'name' => '编辑分类',
        'parent' => 'goods',
      ),
      '/goods/goods_type_list' => 
      array (
        'name' => '类型管理',
        'parent' => 'goods',
      ),
      '/goods/goods_type_edit' => 
      array (
        'name' => '类型编辑',
        'parent' => 'goods',
      ),
      '/goods/goods_spec_list' => 
      array (
        'name' => '规格管理',
        'parent' => 'goods',
      ),
      '/goods/goods_spec_edit' => 
      array (
        'name' => '规格编辑',
        'parent' => 'goods',
      ),
      '/goods/brand_list' => 
      array (
        'name' => '品牌管理',
        'parent' => 'goods',
      ),
      '/goods/brand_edit' => 
      array (
        'name' => '品牌编辑',
        'parent' => 'goods',
      ),
      '/customer/customer_list' => 
      array (
        'name' => '会员管理',
        'parent' => 'customer',
      ),
      '/order/order_list' => 
      array (
        'name' => '订单列表',
        'parent' => 'order',
      ),
      '/order/express_template_list' => 
      array (
        'name' => '快递单模板',
        'parent' => 'express',
      ),
      '/order/express_template_edit' => 
      array (
        'name' => '快递单模板编辑',
        'parent' => 'express',
      ),
      '/order/ship_list' => 
      array (
        'name' => '发货点管理',
        'parent' => 'express',
      ),
      '/order/ship_edit' => 
      array (
        'name' => '发货点编辑',
        'parent' => 'express',
      ),
      '/order/doc_receiving_list' => 
      array (
        'name' => '收款单',
        'parent' => 'receipt',
      ),
      '/order/doc_invoice_list' => 
      array (
        'name' => '发货单',
        'parent' => 'receipt',
      ),
      '/order/doc_refund_list' => 
      array (
        'name' => '退款单',
        'parent' => 'receipt',
      ),
      '/order/doc_returns_list' => 
      array (
        'name' => '售后单',
        'parent' => 'receipt',
      ),
      '/distributor/distributor_edit' => 
      array (
        'name' => '分销商编辑',
        'parent' => 'distributor',
      ),
      '/distributor/distributor_list' => 
      array (
        'name' => '分销商列表',
        'parent' => 'distributor',
      ),
      '/orderscanner/scannersetting_list' => 
      array (
        'name' => '扫描枪设置',
        'parent' => 'scannersetting',
      ),
      '/orderscanner/scannersetting_add' => 
      array (
        'name' => '扫描枪(新增/编辑)',
        'parent' => 'scannersetting',
      ),
      '/orderscanner/scannerorder' => 
      array (
        'name' => '单据扫描',
        'parent' => 'order',
      ),
      '/count/index'=>
      array(
        'name'=>'订单统计',
        'parent'=>'count'
      ),
      '/count/hot'=>
      array(
        'name'=>'热销统计',
        'parent'=>'count'
      ),
      '/count/area_buy'=>
      array(
        'name'=>'地区统计',
        'parent'=>'count'
      ),
      '/count/user_reg'=>
      array(
        'name'=>'会员分布统计',
        'parent'=>'customer_count'
      ),
    ),
  ),
);



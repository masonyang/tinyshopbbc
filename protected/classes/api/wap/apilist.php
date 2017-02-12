<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 18/1/17
 * Time: 下午11:20
 *
 * 分店h5 api列表
 *
 * http://a.test.com/index.php?con=api&act=index&method=wapapilist
 *
 */
class wapapilist extends qqclistapi
{

    protected $apiTitle = '分店h5 API 文档';

    protected $default_method = 'wapapilist';

    protected $apilist = array(
        'wptest'=>'wptest',
        'paylinkv'=>'wppaylink',
        'syncdopay'=>'wppaylink',
        'sms'=>'wpsms',
        'adposition'=>'wpadposition',
        'list'=>'wpattention',
        'add'=>'wpattention',
        'cancel'=>'wpattention',
        'siteconf'=>'wpsiteconf',
        'articlelist'=>'wparticlelist',
        'articledetail'=>'wparticlelist',
        'cservice'=>'wpcservice',
        'addcart'=>'wpcarts',
        'docheckout'=>'wpcarts',
        'checkout'=>'wpcarts',
        'scount'=>'wpcarts',
        'cindex'=>'wpcarts',
        'changecheckout'=>'wpcarts',
        'removecart'=>'wpcarts',
        'advert'=>'wpadvert',
        'captchacode'=>'wpcaptchacode',
        'scategory'=>'wpgcat',
        'category'=>'wpgcat',
        'goods'=>'wpgoods',
        'products'=>'wpproducts',
        'recommend'=>'wpproducts',
        'login'=>'wpcustomer',
        'register'=>'wpcustomer',
        'forgetpwd'=>'wpcustomer',
        'address'=>'wpcustomer',// 收货地址管理
        'addaddr'=>'wpcustomer',// 获取单个收货地址信息
        'doaddr'=>'wpcustomer',// 添加/编辑/删除/设置默认收货地址
        'uinfo'=>'wpcustomer',// 获取会员信息
        'arealist'=>'wparealist',
        'ordercancel'=>'wporders',
        'orderdetail'=>'wporders',
        'morders'=>'wporders',
    );

}
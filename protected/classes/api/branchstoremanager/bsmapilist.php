<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/12/16
 * Time: 上午11:03
 *
 * 分店店掌柜api列表
 *
 * http://a.test.com/index.php?con=api&act=index&method=bsmapilist
 *
 */
class bsmapilist extends qqclistapi
{

    protected $apiTitle = '分店店掌柜 API 文档';

    protected $default_method = 'bsmapilist';

    protected $apilist = array(
        'bsmcaptchacode'=>'bsmcaptchacode',
        'login'=>'bsmmanager',
        //'loginout'=>'bsmmanager',
        'uck'=>'bsmmanager',
        'usrlist'=>'bsmcustomer',
        'goodslist'=>'bsmgoods',
        'goodsdetail'=>'bsmgoods',
        'goodssave'=>'bsmgoods',
        'orderslist'=>'bsmorders',
        'ordersdetail'=>'bsmorders',
        'commission'=>'bsmdistributor',
//        'bsmcommission'=>'bsmcommission',
    );

    public function __construct($params = array())
    {
        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Expose-Headers:X-Reddit-Tracking, X-Moose;');

        $this->params = $params;

        $this->verifyDomain();

    }

    protected function verifyDomain()
    {
        $serverName = Tiny::getServerName();

        if(!$serverName || ($serverName['top'] != 'zd')){
            echo 'haha';
            exit;
        }


    }

}
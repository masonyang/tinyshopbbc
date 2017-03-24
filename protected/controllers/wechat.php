<?php
/**
 * 微信相关
 * @author  maskwang
 * @since   2017/3/24 上午11:48
 */
class WechatController extends Controller
{
    public function init()
    {
    }

    /**
     * 主要用于微信功能对接
     */
    public function index()
    {
        if( isset($_GET['echostr']) ) {
            // 原样返回 才算接入成功
            echo $_GET['echostr'];
        }
        else
        {
            echo 'null';
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 18/1/17
 * Time: 下午11:23
 *
 * 只是测试
 *
 * http://a.test.com/index.php?con=api&act=index&method=wptest
 *
 */
class wptest extends wapbase
{
    public static $title = array(
        'wptest'=>'只是测试',
    );

    public static $lastmodify = array(
        'wptest'=>'2017-1-18',
    );

    public static $notice = array(
        'wptest'=>'',
    );

    public static $requestParams = array(
        'wptest'=>array(
            array(
                'colum'=>'name',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'用户名',
            ),
            array(
                'colum'=>'password',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'密码',
            ),
            array(
                'colum'=>'vaildcode',
                'required'=>'是',
                'type'=>'string',
                'content'=>'图片验证码',
            ),
            array(
                'colum'=>'rand',
                'required'=>'是',
                'type'=>'string',
                'content'=>'随机串为 手机序列号',
            ),
        ),
    );

    public static $responsetParams = array(
        'wptest'=>array(
            array(
                'colum'=>'name',
                'content'=>'姓名',
            ),
            array(
                'colum'=>'mid',
                'content'=>'管理员id',
            ),
            array(
                'colum'=>'shop_id',
                'content'=>'店铺id',
            ),
            array(
                'colum'=>'bmsmd5',
                'content'=>'加密字符串=店铺id+分店管理员id',
            ),
            array(
                'colum'=>'login_time',
                'content'=>'2016-12:13:15',
            ),
            array(
                'colum'=>'login_timestamp',
                'content'=>'时间戳',
            ),
        ),
    );

    public static $requestUrl = array(
        'wptest'=>'     /index.php?con=api&act=index&method=wptest',
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        echo 2323;exit;

    }


    public function wptest_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'name'=>'测试分销商',
                        'mid'=>'管理员id',
                        'shop_id'=>'店铺id',
                        'bmsmd5'=>'加密字符串=店铺id+分店管理员id',
                        'login_time'=>'2016-06-07 13:12:15',
                        'login_timestamp'=>'时间戳'
                    ),
                ),
            )
        );

    }

}
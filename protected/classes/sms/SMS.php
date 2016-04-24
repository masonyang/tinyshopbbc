<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 23/4/16
 * Time: 下午5:29
 * https://api.alidayu.com/doc2/apiDetail?spm=a3142.7802752.6.3.48OgVH&apiId=25450
 */
class SMS
{
    private static $instance = null;

    private $sms_url = 'http://gw.api.taobao.com/router/rest';

    private $sms_params = array(
        'method'=>'',//API接口名称
        'app_key'=>'',//TOP分配给应用的AppKey
        'timestamp'=>'',//'Y-m-d H:i:s'
        'format'=>'json',
        'v'=>'2.0',
        'sign_method'=>'md5',
        'sign'=>'',
        'sms_type'=>'normal',
        'sms_free_sign_name'=>'normal',
        'sms_param'=>'',
        'rec_num'=>'',
        'sms_template_code'=>'',
    );

    public static function getInstance()
    {
        if(null == self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function buildParams($params = array())
    {

    }
}
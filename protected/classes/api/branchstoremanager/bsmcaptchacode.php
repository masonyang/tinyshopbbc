<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午5:42
 */
class bsmcaptchacode extends basmbase
{
    //验证码变量名
    protected $captchaKey = 'bsmVcode';

    protected $safebox;

    public static $title = array(
        'bsmcaptchacode'=>'验证码显示 ok'
    );

    public static $notice = array(
        'bsmcaptchacode'=>'',
    );

    public static $lastmodify = array(
        'bsmcaptchacode'=>'2016-12-17'
    );

    public static $requestParams = array(
        'bsmcaptchacode'=>array(
            array(
                'colum'=>'rand',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'手机的序列号，用手机的序列号作为存储验证码时候 的key。在提交验证码 表单信息的 时候 也要传这个 手机序列号 来作为验证依据',
            ),
        ),
    );

    public static $responsetParams = array(
        'bsmcaptchacode'=>array(
            array(
                'colum'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $requestUrl = array(
        'bsmcaptchacode'=>'     /index.php?con=api&act=index&method=bsmcaptchacode'
    );

    public function __construct($params)
    {

        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');

        $this->params = $params;

        $this->verifyDomain();

    }

    public function index()
    {
        ob_start();
        $this->layout = null;

        $w=$this->params['w']===null?120:intval($this->params['w']);
        $h=$this->params['h']===null?50:intval($this->params['h']);
        $l=$this->params['l']===null?4:intval($this->params['l']);
        $bc=$this->params['bc']===null?array(255,255,255):'#'.$this->params['bc'];
        $c=$this->params['c']===null?null:'#'.$this->params['c'];
        $captcha = new Captcha($w,$h,$l,$bc,$c);
        $captcha->createImage($code);

        $rand = $this->captchaKey.$this->params['rand'];

        $cacheModel = new Model('cache');

        $as = $cacheModel->where('`key`="'.$rand.'"')->find();
//verifyCode3a466d6d-ce54-4ae0-875d-8ee1c5ff6411
        if($as){
            $cacheModel->data(array('content'=>$code))->where('`key`="'.$rand.'"')->update();
        }else{
            $cacheModel->data(array('key'=>$rand,'content'=>$code))->insert();
        }


        ob_end_flush();
    }

    public function bsmcaptchacode_demo()
    {
        return '直接输出 验证码图片';
    }

}